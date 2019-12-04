<?php

namespace Encore\Admin\Config;

use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ConfigController
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('配置管理')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * Edit interface.
     *
     * @param int     $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('配置管理')
            ->description('修改')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('配置管理')
            ->description('新增')
            ->body($this->form());
    }

    public function show($id, Content $content)
    {
        return $content
            ->header('配置管理')
            ->description('详情')
            ->body(Admin::show(ConfigModel::findOrFail($id), function (Show $show) {
                $show->id();
                $show->name();
                $show->value();
                $show->description();
                $show->created_at();
                $show->updated_at();
            }));
    }

    public function grid()
    {
        $grid = new Grid(new ConfigModel());

        $grid->id('ID')->sortable();
        $grid->name()->display(function ($name) {
            return "<a tabindex=\"0\" class=\"btn btn-xs btn-twitter\" role=\"button\" data-toggle=\"popover\" data-html=true title=\"Usage\" data-content=\"<code>config('$name');</code>\">$name</a>";
        });
        $grid->value();
        $grid->description();

        $grid->created_at();
        $grid->updated_at();

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name');
            $filter->like('value');
        });

        return $grid;
    }

    public function form()
    {
        $form = new Form(new ConfigModel());

        $form->display('id', 'ID');
        $form->text('name', '属性名称')->rules('required');
        $form->textarea('value', '属性值');
        $form->image('image', '图片')->move('images/config');
        $form->textarea('description', '描述');
        $form->display('created_at');
        $form->display('updated_at');

        //保存前回调
        $form->saved(function (Form $form) {
            if ($form->model()->image) {
                ConfigModel::where('id', $form->model()->id)->update(['value' => $form->model()->imageUrl]);
            }
        });

        return $form;
    }
}
