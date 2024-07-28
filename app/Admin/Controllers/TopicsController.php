<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Models\Topic;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;

class TopicsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '话题';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Topic);

        $grid->id('ID')->sortable();
        $grid->title('标题');
        $grid->column('category.name', '类目');
        $grid->column('user.name', '作者');
        $grid->view_count('阅览数量');
        $grid->reply_count('回复数量');


        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            // $actions->disableView();
            // $actions->disableDelete();
        });
        $grid->tools(function ($tools) {
            // 禁用批量删除按钮
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
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
        $show = new Show(Topic::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('body', __('Body'));
        $show->field('user_id', __('User id'));
        $show->field('category_id', __('Category id'));
        $show->field('reply_count', __('Reply count'));
        $show->field('view_count', __('View count'));
        $show->field('last_reply_user_id', __('Last reply user id'));
        $show->field('order', __('Order'));
        $show->field('excerpt', __('Excerpt'));
        $show->field('slug', __('Slug'));
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
        $form = new Form(new Topic());
      
        $form->text('title', '标题')->rules('required');
        // 放在商品名称后面
       
        $form->select('category_id', '类目')->options(function ($id) {
            $category = Category::find($id);
            if ($category) {
                return [$category->id => $category->name];
            }
        })->ajax('/admin/api/categories?is_directory=0');
       
        $form->quill('body', '话题内容')->rules('required');
        
        return $form;
    }


     //自定义页面的方式来展示订单。
     public function show($id, Content $content)
     {
         return $content
             ->header('查看话题')
             // body 方法可以接受 Laravel 的视图作为参数
             ->body(view('admin.topics.show', ['topic' => Topic::find($id)]));
     }
}
