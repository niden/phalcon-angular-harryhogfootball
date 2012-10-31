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
            <?php echo $this->getContent() ?>
        </div> <!-- row -->

        {{ partial('partials/footer') }}
    </div>

    {{ javascript_include(config.app.js.jquery, config.app.js.local) }}
    {{ javascript_include(config.app.js.jquery_ui, config.app.js.local) }}
    {{ javascript_include(config.app.js.bootstrap, config.app.js.local) }}
    {{ javascript_include(config.app.js.angular, config.app.js.local) }}
    {{ javascript_include(config.app.js.angular_resource, config.app.js.local) }}
    {{ javascript_include(config.app.js.angular_ui, config.app.js.local) }}
    {{ javascript_include('js/utils.js') }}

    </body>
</html>