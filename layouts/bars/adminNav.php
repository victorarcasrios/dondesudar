<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="?r=site/index">DondeSudar</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1"> 
            <ul class="nav navbar-nav">
                <li><a href="?r=gyms/index"><?= $t["gyms"] ?></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                        <?= $_SESSION["user"]->nick; ?> <span class="caret"></span>
                        <ul class="dropdown-menu">
                            <li><a href="?r=site/profile"><?= $t["profile"] ?></a></li>
                            <li class="divider"></li>
                            <li><a href="?r=site/logout"><?= $t["logout"] ?></a></li>
                        </ul>
                </li>                
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>