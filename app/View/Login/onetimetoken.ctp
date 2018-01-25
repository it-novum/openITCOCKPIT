<?php
// MIT license
// Based on https://bootsnipp.com/snippets/zD9xl
?>

<div class="login-screen">
    <figure>
        <figcaption>Photo by SpaceX on Unsplash</figcaption>
    </figure>
    <figure>
        <figcaption>Photo by NASA on Unsplash</figcaption>
    </figure>
</div>
<div class="container-fluid">
    <div class="row">
        <div id="particles-js" class="col-xs-12 col-sm-5 col-md-4"></div>
    </div>
</div>

<div class="login-center">
    <div class="min-height container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-5 col-lg-3 col-sm-offset-6 col-md-offset-7 col-lg-offset-9">
                <div class="login" id="card">
                    <div class="login-alert">
                        <?php echo $this->Flash->render(); ?>
                        <?php echo $this->Flash->render('auth'); ?>
                    </div>
                    <div class="login-header">
                        <h1><?php echo h('openITCOCKPIT'); ?></h1>
                        <h4><?php echo h(ucfirst(Configure::read('general.site_name'))); ?></h4>
                    </div>
                    <div class="login-form-div">
                        <div class="front signin_form">
                            <?php echo $this->Form->create('Onetimetoken', [
                                'class'         => 'login-form',
                                'inputDefaults' => [
                                    'wrapInput' => false,
                                    'label'     => false,
                                    'div'       => false,
                                ],
                            ]); ?>

                            <?php echo $this->Form->hidden('id', ['value' => $user_id]); ?>

                            <div class="form-group">
                                <div class="input-group">
                                    <?php echo $this->Form->input('onetimetoken', [
                                        'class'         => 'form-control',
                                        'placeholder'   => __('Type your One-time password'),
                                        'type'          => 'text',
                                        'inputDefaults' => [
                                            'wrapInput' => false,
                                            'label'     => false,
                                            'div'       => false,
                                        ]
                                    ]); ?>
                                    <span class="input-group-addon">
                                <i class="fa fa-lg fa-key"></i>
                            </span>
                                </div>
                            </div>
                            <div class="form-group sign-btn">
                                <button type="submit" class="btn btn-primary pull-right">
                                    <?php echo __('Continue'); ?>
                                </button>
                            </div>
                            <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="footer">
    <div class="container-fluid">
        <div class="row pull-right">
            <div class="col-xs-12">
                <a href="https://openitcockpit.io/" target="_blank" class="btn btn-default">
                    <i class="fa fa-lg fa-globe"></i>
                </a>
                <a href="https://github.com/it-novum/openITCOCKPIT" target="_blank" class="btn btn-default">
                    <i class="fa fa-lg fa-github"></i>
                </a>
                <a href="https://twitter.com/openITCOCKPIT" target="_blank" class="btn btn-default">
                    <i class="fa fa-lg fa-twitter"></i>
                </a>
            </div>
        </div>
    </div>
</div>


