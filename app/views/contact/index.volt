{{ content() }}

<div class='page-header'>
    <h2>Contact Us</h2>
</div>

<p>
    Feel free to send us a message with your thoughts, suggestions or even
    reporting an error.
</p>

{{ form('contact/send', 'class':'form-horizontal') }}
    <fieldset>
        <div class='control-group'>
            <label class='control-label' for='name'>Your Full Name</label>
            <div class='controls'>
                {{ text_field('name', 'class':'input-xlarge') }}
                <p class='help-block'>(required)</p>
            </div>
        </div>
        <div class='control-group'>
            <label class='control-label' for='email'>Email Address</label>
            <div class='controls'>
                {{ text_field('email', 'class':'input-xlarge') }}
                <p class='help-block'>(required)</p>
            </div>
        </div>
        <div class='control-group'>
            <label class='control-label' for='comments'>Comments</label>
            <div class='controls'>
                {{ text_area('comments', 'class':'input-xlarge') }}
                <p class='help-block'>(required)</p>
            </div>
        </div>
        <div class='form-actions'>
            {{ submit_button('Send', 'class':'btn btn-primary btn-large') }}
        </div>
    </fieldset>
{{ end_form() }}
