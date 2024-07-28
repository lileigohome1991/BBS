<?php

namespace App\Admin\Controllers;

use App\Models\Reply;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class RepliesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '话题回复';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Reply);

        $grid->id('id')->sortable();
        // 展示关联关系的字段时，使用 column 方法
        $grid->column('user.name', '回复人');
        $grid->column('topic.title', '回复的话题');
        // $grid->column('topic.user.name', '话题作者');

        $grid->content('回复内容');
        
        // 禁用创建按钮，后台不需要创建订单
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            // 禁用删除和编辑按钮
            $actions->disableDelete();
            // $actions->disableEdit();
            $actions->disableView();
        });
        $grid->tools(function ($tools) {
            // 禁用批量删除按钮
            // $tools->batch(function ($batch) {
            //     $batch->disableDelete();
            // });
        });

        return $grid;
    }

    protected function form()
    {
        $form = new Form(new Reply());
        // 在表单页面中添加一个名为 type 的隐藏字段，值为当前商品类型
        $form->text('content', '回复内容')->rules('required');
    
       
        
        return $form;
    }

   
}
