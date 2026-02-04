<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>

            </div>

            <h4 class="modal-title"><b>{{ __('global.add') }} {{ __('global.employees') }}</b></h4>
            <div class="modal-body">

                <div class="card-body text-left">

                    <form method="POST" action="{{ route('employees.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">{{ __('global.name') }}</label>
                            <input type="text" class="form-control" placeholder="{{ __('global.name') }}" id="name" name="name"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="phone_number">{{ __('global.phone_number') }}</label>
                            <input type="text" class="form-control" placeholder="{{ __('global.placeholder_phone') }}" id="phone_number" name="phone_number"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="address">{{ __('global.address') }}</label>
                            <input type="text" class="form-control" placeholder="{{ __('global.placeholder_address') }}" id="address" name="address"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="birth_date">{{ __('global.birth_date') }}</label>
                            <input type="date" class="form-control" id="birth_date" name="birth_date"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="institution">{{ __('global.institution') }}</label>
                            <input type="text" class="form-control" placeholder="{{ __('global.placeholder_institution') }}" id="institution" name="institution"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="position">{{ __('global.position') }}</label>
                            <input type="text" class="form-control" placeholder="{{ __('global.placeholder_position') }}" id="position" name="position"
                                required />
                        </div>

                        <div class="form-group">
                            <label for="major">{{ __('global.major') }}</label>
                            <input type="text" class="form-control" placeholder="{{ __('global.placeholder_major') }}" id="major" name="major" />
                        </div>

                        
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">{{ __('global.email') }}</label>


                            <input type="email" class="form-control" id="email" name="email">

                        </div>
                        <div class="form-group">
                            <label for="schedule" class="col-sm-3 control-label">{{ __('global.schedule_label') }}</label>


                            <select class="form-control" id="schedule" name="schedule" required>
                                <option value="" selected>- {{ __('global.pleaseSelect') }} -</option>
                                @foreach($schedules as $schedule)
                                <option value="{{$schedule->slug}}">{{$schedule->slug}} -> dari {{$schedule->time_in}}
                                    sampai {{$schedule->time_out}} </option>
                                @endforeach

                            </select>

                        </div>

                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    {{ __('global.submit') }}
                                </button>
                                <button type="reset" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal">
                                    {{ __('global.cancel') }}
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>


        </div>

    </div>
</div>
</div>
