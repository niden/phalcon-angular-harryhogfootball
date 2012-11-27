<!DOCTYPE html>
<html ng-app='HHF'>
    {{ partial('partials/header') }}
    <body>


    <div id="spinner" style="display: none;">
        {{ image('img/ajax-loader.gif') }} Loading ...
    </div>

    {{ partial('partials/navbar') }}

    <div class='container-fluid'>
        <div class='row-fluid'>
            <ul class='breadcrumb'>
                <li>
                    {% for bc in breadcrumbs %}
                    {% if (bc['active']) %}
                    {{ bc['text'] }}
                    {% else %}
                    <a href='{{ bc['link'] }}'>{{ bc['text'] }}</a> <span class='divider'>/</span>
                    {% endif %}
                    {% endfor %}
                </li>
            </ul>
        </div>

        <?php echo $this->flash->output() ?>

        <div class="row-fluid">
            {{ content() }}
        </div> <!-- row -->

        {{ partial('partials/footer') }}
    </div>
    <!--[if lt IE 9]>
    {{ javascript_include(config.app.js.html5shiv, config.app.js.local) }}
    <![endif]-->
    {{ javascript_include(config.app.js.jquery, config.app.js.local) }}
    {{ javascript_include(config.app.js.bs, config.app.js.local) }}
    {{ javascript_include(config.app.js.underscore, config.app.js.local) }}
    {{ javascript_include(config.app.js.angular, config.app.js.local) }}
    {{ javascript_include(config.app.js.angular_resource, config.app.js.local) }}
    {{ javascript_include(config.app.js.prettify, config.app.js.local) }}
    {{ javascript_include(config.app.js.bs_datepicker, config.app.js.local) }}
    {{ javascript_include(config.app.js.bs_timestamp, config.app.js.local) }}
    {{ javascript_include(config.app.js.angular_strap, config.app.js.local) }}
    {{ javascript_include(config.app.js.utils) }}

    </body>
</html>