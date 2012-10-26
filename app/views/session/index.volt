{{  content() }}
    <div class="span6">
        <div class="page-header">
            <h2>Log In</h2>
        </div>
        {{ form('session/login', 'class':'form-inline') }}
            <fieldset>
                <div class="control-group">
                    <label class="control-label">Username</label>
                    <div class="controls">
                        {{ text_field('username', 'size':30, 'class':'input-xlarge') }}
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Password</label>
                    <div class="controls">
                        {{ password_field('password', 'size':30, 'class':'input-xlarge') }}
                    </div>
                </div>
                <div class="form-actions">
                    {{ submit_button('Login', 'class':'btn btn-primary btn-large') }}
                </div>
            </fieldset>
        {{ end_form }}
    </div>
