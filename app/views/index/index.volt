<?php echo $this->getContent() ?>

<div class="hero-unit">
    <h1>
        Welcome to HHF GKB Awards
    </h1>
    <p>
        HHF GKB stands for <em>Harry Hog Football, Game Balls and Kick in the Balls
        Awards</em>
    </p>
    <p>
        This application showcases the power of
        <a href='http://phalconphp.com'>Phalcon PHP</a> Framework as well as
        <a href='http://angularjs.org'>AngularJS</a>. It also serves as a quick
        search and statistics generator for the Game Balls and Kick In The Balls
        awards of the <a href='http://harryhogfootball.com'>Harry Hog Football</a>
        Redskins Fans podcast.
</div>

<div ng-controller='MainCtrl'>
    <div class='span3 ng-cloak' ng-cloak>
        <h2>Hall of Fame</h2>
        <div ng-repeat='gb in data.gameballs'>
            <div>[[gb.total]] - [[gb.name]]</div>
            <div class='progress progress-info'>
                <div class='bar' style='width:[[gb.percent]]%;'></div>
            </div>
        </div>
    </div>
    <div class='span3 ng-cloak' ng-cloak>
        <h2>Hall of Shame</h2>
        <div ng-repeat='kitb in data.kicks'>
            <div>[[kitb.total]] - [[kitb.name]]</div>
            <div class='progress progress-danger'>
                <div class='bar' style='width:[[kitb.percent]]%;'></div>
            </div>
        </div>
    </div>
    <div class='span3'>
        <h2>Connect - Hogline</h2>
        <p>
            Follow Harry Hog Football on Twitter!
            <br />
            <a href='http://twitter.com/#!/harryhog'>@HarryHog</a>
        </p>
        <p>
            Call Harry Hog Football in the US at
            <br />
            <strong>1-77-HARRYHOG</strong>
        </p>
        <p>
            <object type='application/x-shockwave-flash' data='https://clients4.google.com/voice/embed/webCallButton' width='230' height='85'></object>
        </p>
    </div>
    <div class="span3">
        <h2>Sponsors</h2>
        <p>
            <a href='http://www.davidmleeatty.com' title='David Lee Legal Services'>
                {{ image('img/davide-lee.jpg', 'alt': 'David Lee Legal Services', 'title': 'David Lee Legal Services') }}
            </a>
        </p>
        <p>
            <a href='http://football.fantasysports.yahoo.com/f1/177614' title='HHFFFL'>
                {{ image('img/hhfffl.jpg', 'alt': 'HHFFFL', 'title': 'HHFFFL') }}
            </a>
        </p>
        <p>
            <a href='https://www.paypal.com/us/cgi-bin/webscr?cmd=_flow&SESSION=Y-jXZVtqZJVolLl_uTEGnIR0G-x6dvh1eXAKj_pOZFVf6QkhwffdmfzNww4&dispatch=5885d80a13c0db1f8e263663d3faee8db2b24f7b84f1819343fd6c338b1d9d60' title='Donate to HHF!'>
                {{ image('img/help-harry.jpg', 'alt': 'Donate to HHF!', 'title': 'Donate to HHF!') }}
            </a>
        </p>
    </div>
</div>
