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

    {{ javascript_include(config.cdn.js.jquery, config.cdn.local) }}
    {{ javascript_include(config.cdn.js.jquery_ui, config.cdn.local) }}
    {{ javascript_include(config.cdn.js.bootstrap, config.cdn.local) }}
    {{ javascript_include(config.cdn.js.angular, config.cdn.local) }}
    {{ javascript_include(config.cdn.js.angular_resource, config.cdn.local) }}
    {{ javascript_include(config.cdn.js.angular_ui, config.cdn.local) }}
    {{ javascript_include('js/utils.js') }}

    </body>
</html>