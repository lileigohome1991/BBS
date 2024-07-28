<?php

namespace App\Admin\Controllers;

use App\Models\Link;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class LinksController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '推荐';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Link);

        $grid->id('ID')->sortable();
        $grid->title('推荐标题');
        $grid->link('推荐链接');
      

        $grid->actions(function ($actions) {
            // $actions->disableView();
            // $actions->disableDelete();
        });
        $grid->tools(function ($tools) {
            // 禁用批量删除按钮
            // $tools->batch(function ($batch) {
            //     $batch->disableDelete();
            // });
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Link::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('link', __('Link'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Link);

        // 创建一个输入框，第一个参数 title 是模型的字段名，第二个参数是该字段描述
        $form->text('title', '推荐标题')->rules('required');

        // 创建一个选择图片的框
        $form->text('link', '链接地址')->rules('required');

        return $form;
    }
}
