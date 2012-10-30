{{ content() }}

<div class='page-header'>
    <h2>Awards Entry</h2>
</div>

<p>
    Enter the Game Balls and Kick-In-The-Balls Awards
</p>

{{ form('awards/add', 'class': 'form-horizontal') }}
<fieldset>
    <div class='control-group'>
        <label class='control-label'>User</label>
        <div class='controls'>
            {{ select('user_id', users, 'using': ['id', 'name'], 'class': 'input-xlarge') }}
            <p class='help-block'>(required)</p>
        </div>
    </div>

    <div class='control-group'>
        <label class='control-label'>Episode</label>
        <div class='controls'>
            {{ select('episode_id', episodes, 'using': ['id', 'number'], 'class': 'input-xlarge') }}
            <p class='help-block'>(required)</p>
        </div>
    </div>

    <div class='control-group'>
        <label class='control-label'>Player</label>
        <div class='controls'>
            {{ select('player_id', players, 'using': ['id', 'name'], 'class': 'input-xlarge') }}
            <p class='help-block'>(required)</p>
        </div>
    </div>

    <div class='control-group'>
        <label class='control-label'>Award</label>
        <div class='controls'>
            {{ select_static('award', ['0': '', '1': 'Game Ball', '-1': 'Kick In The Balls'], 'class': 'input-xlarge') }}
            <p class='help-block'>(required)</p>
        </div>
    </div>

    <div class='form-actions'>
        {{ submit_button('Save', 'class': 'btn btn-primary btn-large') }}
    </div>
</fieldset>
</form>
