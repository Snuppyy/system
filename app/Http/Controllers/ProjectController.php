<?php

namespace App\Http\Controllers;

use App\Organization;
use App\Position;
use App\Project;
use App\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $projects = Project::leftJoin('organizations', 'organizations.id', '=', 'projects.organization')
            ->select('projects.id', 'projects.author', 'projects.alternate', 'projects.status', 'projects.encoding', 'organizations.name_' . app()->getLocale() . ' as organization_name', 'beginning', 'end', 'projects.name_' . app()->getLocale() . ' as project_name')
            ->get();
        $organiations = Organization::where('status', 1)->select('id', 'name_' . app()->getLocale() . ' as name')->get();
        return view('pages.settings.projects.projects')->with(['projects' => $projects, 'organizations' => $organiations]);
    }

    public function logframe(Request $request)
    {
        $organizations = Organization::where('status', 1)
            ->select('id', 'name_' . app()->getLocale() . ' as name')
            ->get();
        return view('pages.settings.projects.logframeProject')->with(['organizations' => $organizations]);
    }

    public function getStaff(Request $request)
    {
        $positions = Position::leftJoin('regions', 'regions.id', '=', 'positions.region')
            ->where('positions.status', 1)
            ->select('positions.id', 'regions.name_' . app()->getLocale() . ' as region', 'positions.name_' . app()->getLocale() . ' as position')
            ->orderBy('region')
            ->orderBy('position')
            ->get();
        echo <<<HERE
          <form id="selectPosition" class="sel2" data-parsley-validate>
                    <div class="col-lg-4 col-md-12">
            <div class="form-layout wd-100p-force" id="selectPositionWrapper">
                <div class="form-group mg-b-10-force">
                    <label class="form-control-label">
                        Должность:
                        <span class="tx-danger">*</span>

                    </label>

                    <select class="form-control select2"
                            name="position"
                            required
                            data-placeholder="Выберите должность"
                            data-parsley-class-handler="#selectPositionWrapper"
                            data-parsley-errors-container="#selectPositionWrapperErrorContainer"
                            data-parsley-trigger="change">
                                <option></option>
HERE;

        foreach ($positions as $position) {
            echo '<option value="' . $position->id . '">' . $position->position . ' - ' . $position->region . '</option>';
        }
        echo <<<HERE
</select>

                    <div id="selectPositionWrapperErrorContainer"></div>

                </div>
            </div>
        </div>
            
            </form>
HERE;
    }

    public function createStaffGetForm(Request $request)
    {
        $users = User::where('status', '1')
            ->select('id', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();

        echo <<<HERE
        <form id="createPosition" class="row mg-t-20" data-parsley-validate>
        <div class="col-lg-12 col-md-12">
            <div class="form-layout wd-100p-force" id="selectUserWrapper">
                <div class="form-group mg-b-10-force">
                    <label class="form-control-label">
                        Пользователь системы:
                        <span class="tx-danger">*</span>

                    </label>

                    <select class="form-control select2"
                            name="user"
                            required
                            data-placeholder="Выберите пользователя системы"
                            data-parsley-class-handler="#selectUserWrapper"
                            data-parsley-errors-container="#selectUserWrapperErrorContainer"
                            data-parsley-trigger="change">
                        <option></option>
HERE;

        foreach ($users as $user) {
            echo '<option value="' . $user->id . '">' . $user->name . '</option>';
        }

        echo <<<HERE
</select>

                    <div id="selectUserWrapperErrorContainer"></div>

                </div>
            </div>
        </div>
        
        <div class="col-lg-12 col-md-6">
            <div class="form-layout wd-100p-force" id="namePositionWrapper">
                <div class="form-group mg-b-10-force">
                    <label class="form-control-label">
                        Название должности:
                        <span class="tx-danger">*</span>
                    </label>

                    <input class="form-control"
                           type="text"
                           required
                           name="namePosition"
                           placeholder="Укажите название должности"
                           data-parsley-class-handler="#namePositionWrapper"
                           data-parsley-errors-container="#namePositionWrapperErrorContainer"
                           data-parsley-trigger="keyup">

                    <div id="namePositionWrapperErrorContainer"></div>

                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6">
            <div class="form-layout wd-100p-force" id="stakePositionWrapper">
                <div class="form-group mg-b-10-force">
                    <label class="form-control-label">
                        Процентная ставка:
                        <span class="tx-danger">*</span>
                    </label>

                    <input class="form-control"
                           type="number"
                           min="1"
                           max="100"
                           required
                           name="stakePosition"
                           placeholder="Укажите процентную ставку"
                           data-parsley-class-handler="#stakePositionWrapper"
                           data-parsley-errors-container="#stakePositionWrapperErrorContainer"
                           data-parsley-trigger="keyup">

                    <div id="stakePositionWrapperErrorContainer"></div>

                </div>
            </div>
        </div>
        
        <div class="col-lg-12 col-md-12">
            <div class="form-layout wd-100p-force" id="functionalWrapper">
                <div class="form-group mg-b-10-force">
                    <label class="form-control-label">
                        Функциональные обязанности:
                        <span class="tx-danger">*</span>

                    </label>

                    <select id='functional' multiple='multiple' required
                    name="functional[]"
                    data-parsley-class-handler="#functionalWrapper"
                    data-parsley-errors-container="#functionalWrapperErrorContainer"
                    data-parsley-trigger="change">
  <option value='elem_1'>Функциональная обязанность</option>
  <option value='elem_2'>Функциональная обязанность</option>
  <option value='elem_3'>Функциональная обязанность</option>
  <option value='elem_4'>Функциональная обязанность</option>
  <option value='elem_100'>Функциональная обязанность</option>
</select>

                    <div id="functionalWrapperErrorContainer"></div>

                </div>
            </div>
        </div>
</form>
HERE;
    }
}
