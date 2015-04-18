<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="skin-blue">
    <?php $this->beginBody() ?>
    <div class="wrapper">
      <header class="main-header">

        <a href="/" class="logo">Binary Analysis Tool</a>

        <nav class="navbar navbar-static-top" role="navigation">
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>

          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

              <li class="dropdown user user-menu">
                <?php
                if (!Yii::$app->user->isGuest) {
                ?>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <span class="glyphicon glyphicon-user"></span>
                  <span><?= Yii::$app->user->identity->fullname ?: Yii::$app->user->identity->username ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="user-header">
                    <p>
                      <?= Yii::$app->user->identity->fullname ? Yii::$app->user->identity->fullname.' ('.Yii::$app->user->identity->username.')' : Yii::$app->user->identity->username ?><br />
                      <?= Yii::$app->user->identity->email ?>
                    </p>
                  </li>
                  <li class="user-footer">
                    <?php
                    echo Nav::widget([
                        'items' => [
                            [
                                'label' => 'Profile',
                                'url' => ['#'],
                                'options' => [
                                    'class' => 'pull-left'
                                ],
                                'linkOptions' => [
                                    'class' => 'btn btn-default btn-flat'
                                ],
                            ],
                            [
                                'label' => 'Log out',
                                'url' => ['/site/logout'],
                                'options' => [
                                    'class' => 'pull-right'
                                ],
                                'linkOptions' => [
                                    'data-method' => 'post',
                                    'class' => 'btn btn-default btn-flat'
                                ],
                            ],
                        ]
                    ]);
                    ?>
                  </li>
                </ul>
                <?php
                }
                ?>
              </li>
            </ul>
          </div>
        </nav>
      </header>

      <aside class="main-sidebar">
        <section class="sidebar">
          <ul class="sidebar-menu">
            <?php
            if (Yii::$app->user->isGuest) {
                $items = [
                    [
                        'label' => ' Register',
                        'url' => ['/site/registration'],
                    ],
                    [
                        'label' => 'Log in',
                        'url' => ['/site/login'],
                    ]
                ];
            }
            else {
                // check role
                $auth = Yii::$app->authManager;
                $adminRole = $auth->getRole('admin');
                $userRole = $auth->getRole('user');
                $roles = $auth->getRolesByUser(Yii::$app->user->id);
                if (in_array($adminRole, $roles)) {
                    $items = [
                        [
                            'label' => 'Jobs',
                            'url' => ['/job'],
                            'active' => (Yii::$app->controller->id == 'job'),
                        ],
                        [
                            'label' => 'Manufacturers',
                            'url' => ['/manufacturer/index'],
                            'active' => (Yii::$app->controller->id == 'manufacturer'),
                        ],
                    ];
                }
                elseif (in_array($userRole, $roles)) {
                    $items = [
                        [
                            'label' => 'Jobs',
                            'url' => ['/job'],
                            'active' => (Yii::$app->controller->id == 'job'),
                        ],
                    ];
                }
            }
            $items = array_merge(
            [ // always visible at top of menu
                [
                    'label' => 'Dashboard',
                    'url' => ['/site/index'],
                ],
            ],
            $items,
            [ // always visible at bottom of menu
                [
                    'label' => 'About',
                    'url' => ['/site/about'],
                ],
            ]);
            echo Nav::widget([
                'items' => $items,
                'encodeLabels' => false
            ]);
            ?>
          </ul>
        </section>
      </aside>

      <div class="content-wrapper">
        <section class="content-header">
          <h1><?= Html::encode($this->title) ?></h1>
          <?= Breadcrumbs::widget([
                'tag' => 'ol',
                'homeLink' => [
                    'label' => 'Dashboard',
                    'url' => '/',
                    'template' => "<li>{link}</li>\n",
                ],
                'itemTemplate' => "<li>{link}</li>\n",
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]);
          ?>
        </section>

        <section class="content">
          <?= Alert::widget() ?>
          <?= $content ?>
        </section>
      </div>

      <footer class="main-footer">
        <div class="pull-right hidden-xs">&copy; <?= date('Y') ?> <a href="http://www.tjaldur.nl/">Tjaldur Software Governance Solutions</a>.</div>&nbsp;
      </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
