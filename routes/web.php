<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Modules\Dashboard\DashboardsController;
use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\Auth\ResetPasswordController;
use App\Http\Controllers\Backend\Auth\ForgotPasswordController;
use App\Http\Controllers\Backend\Modules\Admin\AdminsController;
use App\Http\Controllers\Backend\Modules\Admin\RolesController;
use App\Http\Controllers\Backend\Modules\Category\CategoriesController;
use App\Http\Controllers\Backend\Modules\Blog\BlogsController;
use App\Http\Controllers\Backend\Modules\Contact\ContactsControllerBackend;
use App\Http\Controllers\Backend\Modules\Page\PagesController;
use App\Http\Controllers\Backend\Modules\Settings\CacheController;
use App\Http\Controllers\Backend\Modules\Settings\LanguagesController;
use App\Http\Controllers\Frontend\FrontPagesController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
|
| Admin Panel Route List
|
*/

