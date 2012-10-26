<?php use \Phalcon\Tag as Tag; ?>

<?php echo $this->getContent() ?>

<div>
    <ul class='nav nav-tabs'>
        <li class='pull-right'>
            {{ addButton }}
        </li>
    </ul>
</div>

<div ng-controller='MainCtrl'>
    <table class='table table-bordered table-striped ng-cloak' ng-cloak>
        <thead>
        <tr>
            <th><a href='' ng-click="predicate='name'; reverse=!reverse">Name</a></th>
            <th><a href='' ng-click="predicate='active'; reverse=!reverse">Active</a></th>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="player in data.results | orderBy:predicate:reverse">
            <td>[[player.name]]</td>
            <td>
                <span ng-show="player.active" class='label label-success'>
                    [[player.activeText]]
                </span>
            </td>
            {% if (addButton) %}
            <td width='1%'><a href='/players/edit/[[player.id]]'><i class='icon-pencil'></i></a></td>
            <td width='1%'><a href='/players/delete/[[player.id]]'><i class='icon-remove'></i></a></td>
            {% endif %}
        </tr>
        </tbody>
    </table>
</div>