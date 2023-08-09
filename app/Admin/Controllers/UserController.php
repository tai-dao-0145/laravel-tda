<?php

namespace App\Admin\Controllers;

use App\Enums\GenderEnum;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Validation\Rule;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->model()->orderByDesc('created_at');
        $grid->column('id', __('ID'));
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'));
        $grid->column('phone', __('Phone'));
        $grid->column('address', __('Address'));
        $grid->gender(__('Gender'))->display(function ($gender) {
            if ($gender == GenderEnum::MALE) {
                return "<span class='label label-info'>". __('MALE') ."</span>";
            }
            if ($gender == GenderEnum::FEMALE) {
                return "<span class='label label-primary'>". __('FEMALE') ."</span>";
            }

            return '';
        });
        $grid->column('date_of_birth', __('Date Birth'));
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->column(1/2, function ($filter) {
                $filter->like('name', __('Name'));
                $filter->like('email', __('Email'));
                $filter->like('phone', __('Phone'));
            });
            $filter->column(1/2, function ($filter) {
                $filter->like('address', __('Address'));
                $filter->equal('gender', __('Gender'))->select([
                    GenderEnum::MALE => __('MALE'),
                    GenderEnum::FEMALE => __('FEMALE'),
                ]);
                $filter->between('date_of_birth', __('Date Birth'))->datetime();
            });
        });
        $grid->export(function ($export){
            $export->filename(__('user'));
            $export->column('gender', function ($value, $original) {
                return __('admin.user.' . $original);
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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('admin.user.id'));
        $show->field('name', __('admin.user.name'));
        $show->field('email', __('admin.user.email'));
        $show->field('phone', __('admin.user.phone'));
        $show->field('address', __('admin.user.address'));
        $show->field('gender', __('admin.user.gender'))->as(function ($gender) {
            if ($gender == GenderEnum::MALE) {
                return __('admin.user.MALE');
            } elseif ($gender == GenderEnum::FEMALE) {
                return __('admin.user.FEMALE');
            } else {
                return $gender;
            }
        });
        $show->field('date_of_birth', __('admin.user.date_of_birth'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        $form->text('name', __('Name'))->rules('required');
        $form->email('email', __('Email'))->rules(function ($form) {
            if ($form->isCreating()) {
                return 'required|unique:users,email';
            }
            if ($form->isEditing() && $form->model()->email !== $form->input('email')) {
                return [
                    'required',
                    Rule::unique('users', 'email')->ignore($form->model()->id),
                ];
            }

            return 'required';
        }, ['required|unique']);
        $form->mobile('phone', __('Phone'))->rules('required')
            ->options(['mask' => '999 9999 9999']);
        $form->text('address', __('Address'))->rules('required');
        $form->select('gender', __('Gender'))->options([
            GenderEnum::MALE => __('MALE'),
            GenderEnum::FEMALE => __('FEMALE'),
        ]);
        $form->date('date_of_birth', __('Date Birth'))->default(date('Y-m-d'));
        $form->saving(function (Form $form) {
            $form->model()->email_verified_at = date('Y-m-d H:i:s');
        });
        $passwordRule = $form->isCreating() ? 'required|min:8' : ($form->isEditing() ? 'nullable|min:8' : '');
        $form->password('password', __('Password'))
            ->rules($passwordRule)
            ->default('');
        $form->saving(function (Form $form) {
            if ($form->password) {
                $form->password = bcrypt($form->password);
            } elseif (!$form->password && $form->isEditing()) {
                $form->password = $form->model()->password;
            }
        });
        $form->confirm('Edit', 'edit');

        return $form;
    }
}
