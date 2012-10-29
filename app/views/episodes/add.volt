{{ content() }}

<div class='page-header'>
    <h2>Episodes</h2>
</div>

<div ng-controller='addEditEpisodeCtrl'>
{{ form('episodes/add', 'class': 'form-horizontal') }}

<fieldset>
    <div class='control-group'>
        <label class='control-label'>Episode</label>
        <div class='controls'>
            {{ text_field('episodeId', 'class': 'input-xlarge') }}
            <p class='help-block'>(required)</p>
        </div>
    </div>
    <div class='control-group'>
        <label class='control-label'>Date</label>
        <div class='controls'>
            {{ text_field('episodeDate', 'class': 'input-medium', 'id': 'episodeDate', 'ui-date': 'dateOptions') }}
            <p class='help-block'>(required)</p>
        </div>
    </div>
    <div class='control-group'>
        <label class='control-label'>Summary</label>
        <div class='controls'>
            {{ select_static('outcome', 'class': 'input-xlarge') }}
            <?php
                echo Tag::selectStatic(
                    array(
                        'outcome',
                        array('1' => 'Win', '-1' => 'Loss'),
                        'class' => 'input-xlarge'
                    )
                )
            ?>
            <p class='help-block'>(required)</p>
        </div>
    </div>
    <div class='control-group'>
        <label class='control-label'>Summary</label>
        <div class='controls'>
            {{ text_area('summary', 'rows': 5, 'class': 'input-xlarge') }}
            <p class='help-block'>(required)</p>
        </div>
    </div>
    <div class='form-actions'>
        {{ submit_button('Send', 'class': 'btn btn-primary btn-large') }}
    </div>
</fieldset>
{{ end_form }}

</div>