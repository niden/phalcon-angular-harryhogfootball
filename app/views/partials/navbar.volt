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

<?php $this->flash->output() ?>
