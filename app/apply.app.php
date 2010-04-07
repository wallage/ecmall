<?php

/* 申请开店 */
class ApplyApp extends MallbaseApp
{

    function index()
    {
        /* 判断是否开启了店铺申请 */
        if (!Conf::get('store_allow'))
        {
            $this->show_warning('apply_disabled');
            return;
        }

        /* 只有登录的用户才可申请 */
        if (!$this->visitor->has_login)
        {
            $this->login();
            return;
        }

        /* 已申请过或已有店铺不能再申请 */
        $store_mod =& m('store');
        $store = $store_mod->get_info($this->visitor->get('user_id'));
        if ($store)
        {
            if ($store['state'])
            {
                $this->show_warning('user_has_store');
                return;
            }
            else
            {
                $this->show_warning('user_has_application');
                return;
            }
        }
        $sgrade_mod =& m('sgrade');
        $step = isset($_GET['step']) ? intval($_GET['step']) : 1;
        switch ($step)
        {
            case 1:
                $sgrades = $sgrade_mod->find(array(
                    'order' => 'sort_order',
                ));
                foreach ($sgrades as $key => $sgrade)
                {
                    if (!$sgrade['goods_limit'])
                    {
                        $sgrades[$key]['goods_limit'] = LANG::get('no_limit');
                    }
                    if (!$sgrade['space_limit'])
                    {
                        $sgrades[$key]['space_limit'] = LANG::get('no_limit');
                    }
                    $arr = explode(',', $sgrade['functions']);
                    $subdomain = array();
                    foreach ( $arr as $val)
                    {
                        if (!empty($val))
                        {
                            $subdomain[$val] = 1;
                        }
                    }
                    $sgrades[$key]['functions'] = $subdomain;
                    unset($arr);
                    unset($subdomain);
                }
                $this->assign('domain', ENABLED_SUBDOMAIN);
                $this->assign('sgrades', $sgrades);

                $this->assign('page_title', Lang::get('title_step1') . ' - ' . Conf::get('site_title'));
                $this->display('apply.step1.html');
                break;
            case 2:
                $sgrade_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
                $sgrade = $sgrade_mod->get($sgrade_id);
                if (empty($sgrade))
                {
                    $this->show_message('request_error',
                        'back_step1', 'index.php?app=apply');
                         exit;
                }

                if (!IS_POST)
                {
                    $region_mod =& m('region');
                    $this->assign('site_url', site_url());
                    $this->assign('regions', $region_mod->get_options(0));
                    $this->assign('scategories', $this->_get_scategory_options());

                    /* 导入jQuery的表单验证插件 */
                    $this->import_resource(array('script' => 'mlselection.js,jquery.plugins/jquery.validate.js'));

                    $this->assign('page_title', Lang::get('title_step2') . ' - ' . Conf::get('site_title'));
                    $this->display('apply.step2.html');
                }
                else
                {
                    $store_mod  =& m('store');

                    $store_id = $this->visitor->get('user_id');
                    $data = array(
                        'store_id'     => $store_id,
                        'store_name'   => $_POST['store_name'],
                        'owner_name'   => $_POST['owner_name'],
                        'owner_card'   => $_POST['owner_card'],
                        'region_id'    => $_POST['region_id'],
                        'region_name'  => $_POST['region_name'],
                        'address'      => $_POST['address'],
                        'zipcode'      => $_POST['zipcode'],
                        'tel'          => $_POST['tel'],
                        'sgrade'       => $sgrade['grade_id'],
                       //'apply_remark' => $_POST['apply_remark'],
                        'state'        => $sgrade['need_confirm'] ? 0 : 1,
                        'add_time'     => gmtime(),
                    );
                    $image = $this->_upload_image($store_id);
                    if ($this->has_error())
                    {
                        $this->show_warning($this->get_error());

                        return;
                    }
                    if ($store_mod->add(array_merge($data, $image)) === false)
                    {
                        $this->show_warning($store_mod->get_error());
                        return;
                    }

                    $cate_id = intval($_POST['cate_id']);
                    if ($cate_id > 0)
                    {
                        $store_mod->createRelation('has_scategory', $store_id, $cate_id);
                    }

                    if ($sgrade['need_confirm'])
                    {
                        $this->show_message('apply_ok',
                            'index', 'index.php');
                    }
                    else
                    {
                        $this->send_feed('store_created', array(
                            'user_id'   => $this->visitor->get('user_id'),
                            'user_name'   => $this->visitor->get('user_name'),
                            'store_url'   => SITE_URL . '/' . url('app=store&id=' . $store_id),
                            'seller_name'   => $data['store_name'],
                        ));
                        $this->_hook('after_opening', array('user_id' => $store_id));
                        $this->show_message('store_opened',
                            'index', 'index.php');
                    }
                }
                break;
            default:
                header("Location:index.php?app=apply&step=1");
                break;
        }
    }

    function check_name()
    {
        $store_name = empty($_GET['store_name']) ? '' : trim($_GET['store_name']);

        $store_mod =& m('store');
        if (!$store_mod->unique($store_name))
        {
            echo ecm_json_encode(false);
            return;
        }
        echo ecm_json_encode(true);
    }

    /* 上传图片 */
    function _upload_image($store_id)
    {
        import('uploader.lib');
        $uploader = new Uploader();
        $uploader->allowed_type(IMAGE_FILE_TYPE);
        $uploader->allowed_size(SIZE_STORE_CERT); // 400KB

        $data = array();
        for ($i = 1; $i <= 3; $i++)
        {
            $file = $_FILES['image_' . $i];
            if ($file['error'] == UPLOAD_ERR_OK)
            {
                if (empty($file))
                {
                    continue;
                }
                $uploader->addFile($file);
                if (!$uploader->file_info())
                {
                    $this->_error($uploader->get_error());
                    return false;
                }

                $uploader->root_dir(ROOT_PATH);
                $dirname   = 'data/files/mall/application';
                $filename  = 'store_' . $store_id . '_' . $i;
                $data['image_' . $i] = $uploader->save($dirname, $filename);
            }
        }
        return $data;
    }

    /* 取得店铺分类 */
    function _get_scategory_options()
    {
        $mod =& m('scategory');
        $scategories = $mod->get_list();
        import('tree.lib');
        $tree = new Tree();
        $tree->setTree($scategories, 'cate_id', 'parent_id', 'cate_name');
        return $tree->getOptions();
    }
}

?>
