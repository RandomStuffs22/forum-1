<?php

/*
+--------------------------------------------------------------------------
|   Invision Power Board v1.2
|   ========================================
|   by Matthew Mecham
|   (c) 2001 - 2003 Invision Power Services
|   http://www.invisionpower.com
|   ========================================
|   Web: http://www.invisionboard.com
|   Email: matt@invisionpower.com
|   Licence Info: http://www.invisionboard.com/?license
+---------------------------------------------------------------------------
|
|   > Admin D-Site Functions
|   > Category management routine
|   > Module written by Anton
|   > Date started: 2nd june 2005
|
|   > Module Version Number: 1.0.0
|
|   > Copyright (c) Anton, 2004-2005
|   > E-mail: anton@sources.ru
+--------------------------------------------------------------------------
*/





$idx = new ad_csite_cat();

class ad_csite_cat {

        var $base_url;

        function ad_csite_cat() {
                global $IN, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

                //---------------------------------------
                // Kill globals - globals bad, Homer good.
                //---------------------------------------

                $tmp_in = array_merge( $_GET, $_POST, $_COOKIE );

                foreach ( $tmp_in as $k => $v )
                {
                        unset($$k);
                }

                //---------------------------------------

                switch($IN['code'])
                {
                        case 'new':
                                $this->new_form();
                                break;
                        case 'donew':
                                $this->do_new();
                                break;
                        //+-------------------------
                        case 'edit':
                                $this->edit_form();
                                break;
                        case 'doedit':
                                $this->do_edit();
                                break;
                        //+-------------------------
                        case 'delete':
                                $this->delete_form();
                                break;
                        case 'dodelete':
                                $this->do_delete();
                                break;
                        //+-------------------------
                        default:
                                $this->show_list();
                                break;
                }

        }


        //+---------------------------------------------------------------------------------
        //
        // REMOVE FORUM
        //
        //+---------------------------------------------------------------------------------

        function delete_form() {
                global $IN, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

                $form_array = array();

                if ($IN['cid'] == "")
                {
                        $ADMIN->error("Could not determine the forum ID to delete.");
                }

                $DB->query("SELECT id, name FROM ibf_forums ORDER BY position");

                //+-------------------------------
                // Make sure we have more than 1
                // forum..
                //+-------------------------------

                if ($DB->get_num_rows() < 2)
                {
                        $ADMIN->error("Can not remove this forum, please create another before attempting to remove this one");
                }

                while ( $r = $DB->fetch_row() )
                {
                        if ($r['id'] == $IN['f'])
                        {
                                $name = $r['name'];
                                continue;
                        }

                        $form_array[] = array( $r['id'] , $r['name'] );
                }

                //+-------------------------------

                $ADMIN->page_title = "Removing forum '$name'";

                $ADMIN->page_detail = "Before we remove this forum, we need to determine what to do with any topics and posts you may have left in this forum.";

                //+-------------------------------

                $ADMIN->html .= $SKIN->start_form( array( 1 => array( 'code'  , 'dodelete'),
                                                                                                  2 => array( 'act'   , 'forum'     ),
                                                                                                  3 => array( 'f'     , $IN['f']  ),
                                                                                        ) );

                //+-------------------------------

                $SKIN->td_header[] = array( "&nbsp;"  , "40%" );
                $SKIN->td_header[] = array( "&nbsp;"  , "60%" );

                //+-------------------------------

                $ADMIN->html .= $SKIN->start_table( "Required" );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>Forum to remove: </b>" , $name )      );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>Move all <i>existing topics and posts in this forum</i> to which forum?</b>" ,
                                                                                                  $SKIN->form_dropdown( "MOVE_ID", $form_array )
                                                                             )      );

                $ADMIN->html .= $SKIN->end_form("Move topics and delete this forum");

                $ADMIN->html .= $SKIN->end_table();

                $ADMIN->output();


        }

        //+---------------------------------------------------------------------------------

        function do_delete() {
                global $IN, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

                $DB->query("SELECT * FROM ibf_forums WHERE id='".$IN['f']."'");
                $forum = $DB->fetch_row();

                if ($IN['f'] == "")
                {
                        $ADMIN->error("Could not determine the source forum ID.");
                }

                if ($IN['MOVE_ID'] == "")
                {
                        $ADMIN->error("Could not determine the destination forum ID.");
                }

                // Move topics...

                $DB->query("UPDATE ibf_topics SET forum_id='".$IN['MOVE_ID']."' WHERE forum_id='".$IN['f']."'");

                // Move posts...

                $DB->query("UPDATE ibf_posts SET forum_id='".$IN['MOVE_ID']."' WHERE forum_id='".$IN['f']."'");

                // Move polls...

                $DB->query("UPDATE ibf_polls SET forum_id='".$IN['MOVE_ID']."' WHERE forum_id='".$IN['f']."'");

                // Move voters...

                $DB->query("UPDATE ibf_voters SET forum_id='".$IN['MOVE_ID']."' WHERE forum_id='".$IN['f']."'");

                // Delete the forum

                $DB->query("DELETE FROM ibf_forums WHERE id='".$IN['f']."'");

                // Delete any moderators, if any..

                $DB->query("DELETE FROM ibf_moderators WHERE forum_id='".$IN['f']."'");


                $this->recount($IN['MOVE_ID']);

                // Have we moved this forum from a sub cat forum?
                // If so, are there any forums left in this sub cat forum?

                if ($forum['parent_id'] > 0)
                {
                        $DB->query("SELECT id FROM ibf_forums WHERE parent_id='{$forum['parent_id']}'");

                        if ( ! $DB->get_num_rows() )
                        {
                                // No, there are no more forums that have a parent id the same as the one we've just moved it from
                                // So, make that forum a normal forum then!

                                $DB->query("UPDATE ibf_forums SET subwrap=0 WHERE id='{$forum['parent_id']}'");
                        }
                }

                $ADMIN->save_log("Removed forum '{$forum['name']}'");

                $ADMIN->done_screen("Forum Removed", "Forum Control", "act=cat" );

        }


        //+---------------------------------------------------------------------------------
        //
        // NEW FORUM
        //
        //+---------------------------------------------------------------------------------


        function new_form() {
                global $IN, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP, $NAV;


                if ($_GET['name'] != "")
                {
                        $f_name = $std->txt_stripslashes(urldecode($_GET['name']));
                }

                $cats = array();

                $cats = $NAV->build_cat_list_select_ad();

                $ADMIN->page_title = "�������� ����� ������";

                $ADMIN->page_detail = "";

                //+-------------------------------

                $ADMIN->html .= $SKIN->start_form( array( 1 => array( 'code'  , 'donew'  ),
                                                                                                  2 => array( 'act'   , 'csite_cat'  ),
                                                                                        ) );

                //+-------------------------------

                $SKIN->td_header[] = array( "&nbsp;"  , "40%" );
                $SKIN->td_header[] = array( "&nbsp;"  , "60%" );

                //+-------------------------------

                $ADMIN->html .= $SKIN->start_table( "Basic Settings" );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>�������� ������������ ������</b><br>" ,
                                                                                                  "<select name='parent_id'>" .
                                                                                                  "<option value='0'>������� �������</option>" .
                                                                                                  $cats .
                                                                                                  "</select>",
                                                                             )      );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>����������� ������� �� �����</b>" ,
                                                                                                  $SKIN->form_dropdown( "visible",
                                                                                                                                                        array(
                                                                                                                                                                        0 => array( 0, '�������' ),
                                                                                                                                                                        1 => array( 1, '�������'  ),
                                                                                                                                                                 ),
                                                                                                                                                  "1"
                                                                                                                                            )
                                                                             )      );

                $ADMIN->html .= $SKIN->end_table();

                //+-------------------------------

                $SKIN->td_header[] = array( "&nbsp;"  , "40%" );
                $SKIN->td_header[] = array( "&nbsp;"  , "60%" );

                $ADMIN->html .= $SKIN->start_table( "������ ������" );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>�������� �������</b>" ,
                                                                                                  $SKIN->form_input("name", $name)
                                                                             )      );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>ID ������� (������ ���������� ����� � �����!)</b><br>�� ������ ������� ���� ����� �������
                                                                                ���������� � ������� �������" ,
                                                                                                  $SKIN->form_input("category_id", $category_id)
                                                                             )      );


                $ADMIN->html .= $SKIN->add_td_row( array( "<b>������� ��������</b><br>You may use HTML - linebreaks are converted 'Auto-Magically'" ,
                                                                                                  $SKIN->form_textarea("description")
                                                                             )      );

                //+-------------------------------

                $ADMIN->html .= $SKIN->end_table();


                $SKIN->td_header[] = array( "&nbsp;"  , "40%" );
                $SKIN->td_header[] = array( "&nbsp;"  , "60%" );

                $ADMIN->html .= $SKIN->start_table( "��������������� �� ������ ��������" );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>URL ��� ���������������</b><br>��� ���������� ���������������<br>
                                                                                             �������� ��� ���� ������������." ,
                                                                                                  $SKIN->form_input("redirect_url")
                                                                                 )      );

                $ADMIN->html .= $SKIN->end_table();

                //+-------------------------------

                $SKIN->td_header[] = array( "&nbsp;"  , "40%" );
                $SKIN->td_header[] = array( "&nbsp;"  , "60%" );

                $ADMIN->html .= $SKIN->start_table( "���������� ������ � �������" );

                //+-------------------------------

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>��������� ���������� ����� ����� ������ � ������?</b><br>��� ���������� ���������� ����� ����� ������, ����������� ������ ����� ��������� ����� ��� ������ � ������." ,
                                                                                                  $SKIN->form_yes_no("one_article", 1 )
                                                                             )      );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>��������� ���������� ������ � ���� ������?</b>" ,
                                                                                                  $SKIN->form_yes_no("allow_posts", 1 )
                                                                             )      );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>��������� ����� ����������� ������� �� ������ � ������� ����?</b>" ,
                                                                                                  $SKIN->form_yes_no("show_subcats", 1 )
                                                                             )      );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>��������� ��������� ���������������� ������������ � ������� ������� �������?</b>" ,
                                                                                                  $SKIN->form_yes_no("allow_comments", 1 )
                                                                             )      );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>���������� ������� ��������� � ����� ���������� ������?</b><br>��� ������� ����� �������� ������ ��� ������������ ��-����� ����������" ,
                                                                                                  $SKIN->form_yes_no("show_smilies", 1 )
                                                                             )      );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>���������� ������ � ����� ��������� �� ������ �����?</b><br>(��� ������ ������� ����)" ,
                                                                                                  $SKIN->form_yes_no("show_fullscreen", 0 )
                                                                             )      );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>�������� �������� ������ ��� ����������� ������?</b><br>(��� ��������� ����� ������ ����� �������������� ������ ����� ��������� ����� �������� ������.)" ,
                                                                                                  $SKIN->form_yes_no("force_versioning", 1 )
                                                                             )      );



                $ADMIN->html .= $SKIN->add_td_row( array( "<b>����� ���������� ������ ��-���������</b>" ,
                                                                                                  $SKIN->form_dropdown( "add_article_form",
                                                                                                                                                        array(
                                                                                                                                                                        0 => array( 0, 'BB-���� (��������)' ),
                                                                                                                                                                        1 => array( 1, 'WISIWIG-�������� '  ),
                                                                                                                                                                 ),
                                                                                                                                                  "0"
                                                                                                                                            )

                                                                             )      );

                //-----------

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>������������ ������������?</b><br>(Requires a moderator to manually add posts/topics to the forum)" ,
                                                                                                  $SKIN->form_dropdown("moderate", array(
                                                                                                                                                                           0 => array( 1, '������������ ��������/��������� ������' ),
                                                                                                                                                                           1 => array( 2, '������������ ��������� ������' ),
                                                                                                                                                                           2 => array( 0, '���' ),
                                                                                                                                                                             ),
                                                                                                                                                              0 )
                                                                             )      );


                $ADMIN->html .= $SKIN->end_table();

                $ADMIN->html .= $SKIN->start_table( "������� ������" );
                $ADMIN->html .= $SKIN->end_form("������� ������");
                $ADMIN->html .= $SKIN->end_table();

                $ADMIN->html .= $SKIN->end_table();

                $ADMIN->output();


        }


        //------------------------------------------------------------------------------------------------
        //------------------------------------------------------------------------------------------------

        function do_new() {
                global $IN, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP, $NAV;

                $IN['name'] = trim($IN['name']);

                if ($IN['name'] == "")
                {
                        $ADMIN->error("�� ������ ������ �������� �������");
                }

                $IN['category_id'] = trim($IN['category_id']);

                if ($IN['category_id'] == "")
                {
                        $ADMIN->error("�� ������ ������ ID �������");
                }

                //----------------------------------------------
                //  create and write DB string
                //----------------------------------------------

                $db_string = $DB->compile_db_insert_string( array (
                                                                                                                        'name'             => $IN['name'],
                                                                                                                        'description'      => $std->my_nl2br( $std->txt_stripslashes($_POST['description']) ),
                                                                                                                        'category_id'      => $IN['category_id'],
                                                                                                                        'parent_id'        => $IN['parent_id'],
                                                                                                                        'allow_posts'      => $IN['allow_posts'],
                                                                                                                        'redirect_url'     => $IN['redirect_url'],
                                                                                                                        'add_article_form' => $IN['add_article_form'],
                                                                                                                        'moderate'         => $IN['moderate'],
                                                                                                                        'visible'          => $IN['visible'],
                                                                                                                        'one_article'      => $IN['one_article'],
                                                                                                                        'show_subcats'     => $IN['show_subcats'],
                                                                                                                        'allow_comments'   => $IN['allow_comments'],
                                                                                                                        'show_fullscreen'  => $IN['show_fullscreen'],
                                                                                                                        'show_smilies'     => $IN['show_smilies'],
                                                                                                                        'force_versioning' => $IN['force_versioning'],

                                                                                                  )       );

                $DB->query("INSERT INTO ibf_cms_uploads_cat (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")");

                //----------------------------------------------
                //  create local HDD directory
                //----------------------------------------------

                if ( $INFO['csite_cms_path'] ) {

                     $inserted_id = $DB->get_insert_id();

                     //----------------------------------------------
                     //  rebuild cats withe new created cat
                     //----------------------------------------------

                     $NAV->mod_nav();

                     //----------------------------------------------
                     //  build path entry
                     //----------------------------------------------

                     $path = $NAV->build_path( $inserted_id );

                     if ( file_exists($path) === true ) {

                             //----------------------------------------------
                             // delete created DB entry and throw error
                             //----------------------------------------------

                             $DB->query(" DELETE FROM ibf_cms_uploads_cat WHERE id = {$inserted_id} ");

                             $ADMIN->error("���������� � ����� ID ��� ����������, ������� ������ ID");
                     }

                     if ( $this->my_mkdir($path) === false ) {

                             //----------------------------------------------
                             // delete created DB entry and throw error
                             //----------------------------------------------

                             $DB->query(" DELETE FROM ibf_cms_uploads_cat WHERE id = {$inserted_id} ");

                             $ADMIN->error("������ �������� �������. ���������, ��� ����� ������� �� ���������� ''{$INFO['csite_cms_path']}'' ����������� �� ������/������ ��� ���� (0777) ");
                     }

                }
                else {

                        $ADMIN->error("�� ������� ���������� ��� �������� ������ CMS");
                }


                //----------------------------------------------
                //  finished!
                //----------------------------------------------

                $ADMIN->save_log("��� ������ ������ ����� '{$IN['FORUM_NAME']}'");

                $ADMIN->done_screen("������ {$IN['FORUM_NAME']} ������", "���������� ��������� D-Site", "act=csite_cat" );
        }

        //------------------------------------------------------------------------------------------------

        //+---------------------------------------------------------------------------------
        //
        // EDIT FORUM
        //
        //+---------------------------------------------------------------------------------

        function edit_form() {
                global $IN, $INFO, $DB, $SKIN, $ADMIN, $std, $NAV;

                $cat_id = intval( $IN['cid'] );

                if ( $cat_id == 0 )
                {
                        $ADMIN->error("You didn't choose a forum to edit, duh!");
                }

                $DB->query(" SELECT * FROM ibf_cms_uploads_cat WHERE id = {$cat_id} ");

                $dbres = $DB->fetch_row();

                if ( !$dbres ) {

                        $ADMIN->error("������������� ������ �� ������ �� �������");
                }

                $cats = array();

                $cats = $NAV->build_cat_list_select_ad($dbres['id']);

                $ADMIN->page_title = "�������������� ������� ''{$dbres['name']}''";

                $ADMIN->page_detail = "";

                //+-------------------------------

                $ADMIN->html .= $SKIN->start_form( array( 1 => array( 'code'  , 'doedit'  ),
                                                                                                  2 => array( 'act'   , 'csite_cat'  ),
                                                                                                  3 => array( 'cid'   , $cat_id  ),
                                                                                        ) );

                //+-------------------------------

                $SKIN->td_header[] = array( "&nbsp;"  , "40%" );
                $SKIN->td_header[] = array( "&nbsp;"  , "60%" );

                //+-------------------------------

                $ADMIN->html .= $SKIN->start_table( "Basic Settings" );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>������������ ������</b><br>" ,
                                                                                                  "<select name='parent_id'>" .
                                                                                                  "<option value='0'>������� �������</option>" .
                                                                                                  $cats .
                                                                                                  "</select>",
                                                                             )      );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>����������� ������� �� �����</b>" ,
                                                                                                  $SKIN->form_dropdown( "visible",
                                                                                                                                                        array(
                                                                                                                                                                        0 => array( 0, '�������' ),
                                                                                                                                                                        1 => array( 1, '�������'  ),
                                                                                                                                                                 ),
                                                                                                                                                  $dbres['visible']
                                                                                                                                            )
                                                                             )      );

                $ADMIN->html .= $SKIN->end_table();

                //+-------------------------------

                $SKIN->td_header[] = array( "&nbsp;"  , "40%" );
                $SKIN->td_header[] = array( "&nbsp;"  , "60%" );

                $ADMIN->html .= $SKIN->start_table( "������ ������" );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>�������� �������</b>" ,
                                                                                                  $SKIN->form_input("name", $dbres['name'])
                                                                             )      );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>ID ������� (������ ���������� ����� � �����!)</b><br>�� ������ ������� ���� ����� �������
                                                                                ���������� � ������� �������" ,
                                                                                                  $SKIN->form_input("category_id", $dbres['category_id'])
                                                                             )      );


                $ADMIN->html .= $SKIN->add_td_row( array( "<b>������� ��������</b><br>You may use HTML - linebreaks are converted 'Auto-Magically'" ,
                                                                                                  $SKIN->form_textarea("description", $dbres['description'])
                                                                             )      );

                //+-------------------------------

                $ADMIN->html .= $SKIN->end_table();


                $SKIN->td_header[] = array( "&nbsp;"  , "40%" );
                $SKIN->td_header[] = array( "&nbsp;"  , "60%" );

                $ADMIN->html .= $SKIN->start_table( "��������������� �� ������ ��������" );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>URL ��� ���������������</b><br>��� ���������� ���������������<br>
                                                                                             �������� ��� ���� ������������." ,
                                                                                                  $SKIN->form_input("redirect_url", $dbres['redirect_url'])
                                                                                 )      );

                $ADMIN->html .= $SKIN->end_table();

                //+-------------------------------

                $SKIN->td_header[] = array( "&nbsp;"  , "40%" );
                $SKIN->td_header[] = array( "&nbsp;"  , "60%" );

                $ADMIN->html .= $SKIN->start_table( "���������� ������ � �������" );

                //+-------------------------------

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>��������� ���������� ����� ����� ������ � ������?</b><br>��� ���������� ���������� ����� ����� ������, ����������� ������ ����� ��������� ����� ��� ������ � ������." ,
                                                                                                  $SKIN->form_yes_no("one_article", $dbres['one_article'] )
                                                                             )      );


                $ADMIN->html .= $SKIN->add_td_row( array( "<b>��������� ���������� ������ � ���� ������?</b>" ,
                                                                                                  $SKIN->form_yes_no("allow_posts", $dbres['allow_posts'] )
                                                                             )      );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>��������� ����� ����������� ������� �� ������ � ������� ����?</b>" ,
                                                                                                  $SKIN->form_yes_no("show_subcats", $dbres['show_subcats'] )
                                                                             )      );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>��������� ���������� ���������������� ������������ � ������� �������?</b>" ,
                                                                                                  $SKIN->form_yes_no("allow_comments", $dbres['allow_comments'] )
                                                                             )      );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>���������� ������� ��������� � ����� ���������� ������?</b><br>��� ������� ����� �������� ������ ��� ������������ ��-����� ����������" ,
                                                                                                  $SKIN->form_yes_no("show_smilies", $dbres['show_smilies'] )
                                                                             )      );

                //if ( $dbres['show_fullscreen'] == 0 ) $dbres['show_fullscreen'] = 1;
                //if ( $dbres['show_fullscreen'] == 1 ) $dbres['show_fullscreen'] = 0;

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>�������� ������� ���� ��� ��������� ������?</b><br>(������ �� ������ �����)" ,
                                                                                                  $SKIN->form_yes_no("show_fullscreen", $dbres['show_fullscreen'] )
                                                                             )      );

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>�������� �������� ������ ��� ����������� ������?</b><br>(��� ��������� ����� ������ ����� �������������� ������ ����� ��������� ����� �������� ������.)" ,
                                                                                                  $SKIN->form_yes_no("force_versioning", $dbres['force_versioning'] )
                                                                             )      );


                $ADMIN->html .= $SKIN->add_td_row( array( "<b>����� ���������� ������ ��-���������</b>" ,
                                                                                                  $SKIN->form_dropdown( "add_article_form",
                                                                                                                                                        array(
                                                                                                                                                                        0 => array( 0, 'BB-���� (��������)' ),
                                                                                                                                                                        1 => array( 1, 'WISIWIG-�������� '  ),
                                                                                                                                                                 ),
                                                                                                                                                  $dbres['add_article_form']
                                                                                                                                            )

                                                                             )      );

                //-----------

                $ADMIN->html .= $SKIN->add_td_row( array( "<b>������������ ������������?</b><br>(Requires a moderator to manually add posts/topics to the forum)" ,
                                                                                                  $SKIN->form_dropdown("moderate", array(
                                                                                                                                                                           0 => array( 1, '������������ ��������/��������� ������' ),
                                                                                                                                                                           1 => array( 2, '������������ ��������� ������' ),
                                                                                                                                                                           2 => array( 0, '���' ),
                                                                                                                                                                             ),
                                                                                                                                                              $dbres['moderate'] )
                                                                             )      );


                $ADMIN->html .= $SKIN->end_table();

                $ADMIN->html .= $SKIN->start_table( "�������� ������" );
                $ADMIN->html .= $SKIN->end_form("�������� ������");
                $ADMIN->html .= $SKIN->end_table();

                $ADMIN->html .= $SKIN->end_table();

                $ADMIN->output();

        }


        //+---------------------------------------------------------------------------------

        function do_edit() {
                global $IN, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP, $NAV;

                $cat_id = intval( $IN['cid'] );
                $just_moved = false;

                if ( $cat_id == 0 )
                {
                        $ADMIN->error("You didn't choose a forum to edit, duh!");
                }

                $DB->query(" SELECT * FROM ibf_cms_uploads_cat WHERE id = {$cat_id} ");

                $dbres = $DB->fetch_row();

                if ( !$dbres ) {

                        $ADMIN->error("You didn't choose a forum to edit, duh!");
                }

                //----------------------------------------------
                //  are we moving the category to a new path?
                //----------------------------------------------

                if ( $IN['parent_id'] != $dbres['parent_id'] ) {

                        //----------------------------------------------
                        // don't we moving parent to child, yeah?
                        //----------------------------------------------

                        if ( $NAV->is_parent($IN['parent_id'], $dbres['id']) === true ||  $IN['parent_id'] == $dbres['id'] ) {

                                $ADMIN->error("<b>���������� ����������� ������ ��� � ����</b><br>vot, �������! �� �������� ���� ������������ �������� �� ���� � �������� :� %)))");
                        }

                        $old_path = $NAV->build_path($dbres['id']);
                        $new_path = $NAV->build_path( $IN['parent_id'] ) . "/" . $dbres['category_id'];

                        $new_path = preg_replace( "#/$#", "", $new_path);
                        $old_path = preg_replace( "#/$#", "", $old_path);

                        //----------------------------------------------
                        // does the directory exists?
                        //----------------------------------------------

                        if ( file_exists($new_path) ) {

                                $ADMIN->error("<b>������ ����������� ��������� ���������� - ���������� � ����� ������ ��� ����������.</b><br>���������� �������� ID ������� �� ������");
                        }

                        //----------------------------------------------
                        //  copy all subdirs to a new path and delete
                        //  all of the old subdirs
                        //----------------------------------------------

                        if ( $ADMIN->copy_dir($old_path, $new_path) === false ) {

                                $ADMIN->error("<b>������ ����������� ��������� ����������.</b><br>��������� ����� ������� �� ���������� ''{$new_path}'', � ����� ������� ���������� ����� �� ��������}");
                        }

                        $ADMIN->rm_dir($old_path);

                        //----------------------------------------------
                        //  don't allow 'em to chenge dir name
                        //  while moving it to the new destination
                        //----------------------------------------------

                        $IN['category_id'] = $dbres['category_id'];
                }

                //----------------------------------------------
                //  are we renaming the category_id?
                //----------------------------------------------

                if ( $IN['category_id'] != $dbres['category_id'] ) {

                        //----------------------------------------------
                        // does directory exists?
                        //----------------------------------------------

                        $old_path = $NAV->build_path($dbres['id']);
                        $new_path = $NAV->build_path($dbres['id'] );

                        $new_path = preg_replace( "#/$#", "", $new_path);
                        $old_path = preg_replace( "#/$#", "", $old_path);

                        $tmp_path = explode("/", $old_path); end($tmp_path); $old_id = current($tmp_path);


                        //TODO: check if only the last element will be replaced

                        $new_path = preg_replace("/{$old_id}$/i", $IN['category_id'], $new_path);

                        if ( file_exists($new_path) === true ) {

                                $ADMIN->error("<b>������ ����������� ��������� ���������� - ���������� � ����� ������ ��� ����������.</b><br>���������� �������� ID ������� �� ������");
                        }



                        //----------------------------------------------
                        //  copy all subdirs to a new path and delete
                        //  all of the old subdirs
                        //----------------------------------------------

                        if ( $ADMIN->copy_dir($old_path, $new_path) === false ) {

                                $ADMIN->error("<b>������ ����������� ��������� ����������.</b><br>��������� ����� ������� �� ���������� ''{$new_path}'', � ����� ������� ���������� ����� �� ��������}");
                        }

                        $ADMIN->rm_dir($old_path);
                }



                //----------------------------------------------
                //  create and write DB string
                //----------------------------------------------

                $db_string = $DB->compile_db_update_string( array (
                                                                                                                        'name'             => $IN['name'],
                                                                                                                        'description'      => $std->my_nl2br( $std->txt_stripslashes($_POST['description']) ),
                                                                                                                        'visible'          => $IN['visible'],
                                                                                                                        'category_id'      => $IN['category_id'],
                                                                                                                        'parent_id'        => $IN['parent_id'],
                                                                                                                        'allow_posts'      => $IN['allow_posts'],
                                                                                                                        'redirect_url'     => $IN['redirect_url'],
                                                                                                                        'add_article_form' => $IN['add_article_form'],
                                                                                                                        'moderate'         => $IN['moderate'],
                                                                                                                        'one_article'      => $IN['one_article'],
                                                                                                                        'show_subcats'     => $IN['show_subcats'],
                                                                                                                        'allow_comments'   => $IN['allow_comments'],
                                                                                                                        'show_fullscreen'  => $IN['show_fullscreen'],
                                                                                                                        'show_smilies'     => $IN['show_smilies'],
                                                                                                                        'force_versioning' => $IN['force_versioning'],


                                                                                                  )       );

                $DB->query("UPDATE ibf_cms_uploads_cat SET " . $db_string ." WHERE id = " . $cat_id );

                //----------------------------------------------
                //  finished!
                //----------------------------------------------

                $ADMIN->save_log("��� ������� ������ ����� '{$IN['name']}'");

                $ADMIN->done_screen("������ ''{$IN['name']}'' �������", "���������� ��������� D-Site", "act=csite_cat" );

        }

          //--------------------------------------------------------------------
          //  show list of categories with subcategories
          //--------------------------------------------------------------------

          function show_list() {
          global $SKIN, $ADMIN, $NAV;

                //-------------------------------------

                $ADMIN->page_title = "���������� ��������� �����.";

                $ADMIN->page_detail = "";

                $SKIN->td_header[] = array( "�������� �������"  , "80%" );
                $SKIN->td_header[] = array( "����������"  , "20%" );

                //+-------------------------------

                $ADMIN->html .= $SKIN->start_table( "������ ��������" );

                $ADMIN->html .= $NAV->build_cat_list_ad_cat();

                $ADMIN->html .= $SKIN->end_table();

                $ADMIN->output();
          }


          //--------------------------------------------------------------------
          //      mkdir() implementation + setting file access rigths
          //--------------------------------------------------------------------

          function my_mkdir($directory_name) {
          global $ibforums;

                  umask( 002 );

                  if ( file_exists( $directory_name ) ) {

                          return false;
                  }

                  return @mkdir($directory_name);
          }



}


?>
