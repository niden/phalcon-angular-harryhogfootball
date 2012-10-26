<!DOCTYPE html>
<html ng-app='HHF'>
    <head>
        <meta charset="utf-8">
        {{ get_title() }}
        {{ stylesheet_link(config.cdn.css.bootstrap, config.cdn.local) }}
        {{ stylesheet_link(config.cdn.css.jquery_ui, config.cdn.local) }}
        {{ stylesheet_link('css/hhf.css') }}
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Harry Hog Football - Game balls and Kick in the Balls awards finder" />
        <meta name="author" content="niden.net" />
    </head>
    <body>


    <div id="spinner" style="display: none;">
        {{ image('img/ajax-loader.gif') }} Loading ...
    </div>

    <div class='navbar'>
        <div class='navbar-inner'>
            <div class='container-fluid'>
                <a class='btn btn-navbar' data-toggle='collapse' data-target='.nav-collapse'>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                </a>
                <a class='brand' href='/'>HHF GKB Awards</a>
                <div class='nav-collapse'>
                    <ul class='nav pull-left'>
                        {% for menu in menus['left'] %}
                        <li{% if (menu['active']) %} class="active"{% endif %}>
                            <a href='{{ menu['link'] }}'>{{ menu['text'] }}</a>
                        </li>
                        {% endfor %}
                        <li>
                            <a href='{{ menus['rightLink'] }}'>{{ menus['rightText'] }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

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
        <div class="row-fluid">
            <?php echo $this->getContent() ?>
        </div> <!-- row -->
        <hr />
        <footer>
            <p>&copy; niden.net <?php echo date('Y'); ?></p>
        </footer>
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