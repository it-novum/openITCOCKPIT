<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

/**
 * @var \App\View\AppView $this
 * @var bool $isSsoEnabled
 * @var bool $forceRedirectSsousersToLoginScreen
 */

$Logo = new \itnovum\openITCOCKPIT\Core\Views\Logo();
?>
<div class="peers ai-s fxw-nw h-100vh">
    <div id="tsparticles" class="peer peer-greed h-100 pos-r">
        <!-- layout fix -->
    </div>

    <div class="col-12 col-md-4 peer pX-40 pY-80 h-100 scrollable pos-r login-side-bg" style='min-width: 320px;'>

        <div class="col-12 text-center">
            <img class="img-fluid" src="<?= h($Logo->getLoginLogoHtml()); ?>" style="max-height: 230px;"/>
        </div>

        <h4 class="fw-300 c-white mB-40"><?= __('Login') ?></h4>

        <?php if ($isSsoEnabled === true): ?>
            <div class="form-group">
                <a
                    id="sso-redirect-href"
                    href="/users/login?redirect_sso=true"
                    class="btn btn-primary btn-block">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    <?= __('Login through Single sign-on') ?>
                </a>
            </div>
        <?php endif; ?>

        <!-- Start login form for username and password (Session and LDAP) -->
        <?php if ($forceRedirectSsousersToLoginScreen === false): ?>
            <form (ngSubmit)="submit();" *ngIf="!hasValidSslCertificate">
                <div class="form-group">
                    <label class="text-normal c-white"><?= __('Username') ?></label>
                    <input
                        name="email"
                        type="text"
                        class="form-control"
                        placeholder="John Doe"
                        [disabled]="disableLogin"
                        [(ngModel)]="post.email">
                </div>
                <div class="form-group">
                    <label class="text-normal c-white"><?= __('Password') ?></label>
                    <input
                        name="password"
                        type="password"
                        class="form-control"
                        placeholder="Password"
                        [disabled]="disableLogin"
                        [(ngModel)]="post.password">
                </div>
                <div class="form-group">
                    <div class="peers ai-c jc-sb fxw-nw">
                        <div class="peer">
                            <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                                <input
                                    name="RememberMeCheckbox"
                                    type="checkbox"
                                    trueFalseValue trueValue="1" falseValue="0"
                                    [(ngModel)]="post.remember_me"
                                    [disabled]="disableLogin"
                                    id="RememberMeCheckbox"
                                    class="peer">
                                <label for="RememberMeCheckbox" class=" peers peer-greed js-sb ai-c">
                                    <span class="peer peer-greed"><?= __('Remember Me') ?></span>
                                </label>
                            </div>
                        </div>
                        <div class="peer">
                            <button
                                type="submit"
                                class="btn btn-primary"
                                [disabled]="disableLogin">
                        <span>
                            <i class="fa-solid fa-spinner fa-spin" [hidden]="!disableLogin"></i>
                        </span>
                                <?= __('Login') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
        <!-- End form login -->

        <!-- Users with valid SSL certificates are always logged in -->
        <div *ngIf="hasValidSslCertificate">
            <div class="alert alert-success" role="alert">
                <?= __('Authorization through SSL certificate successfully.') ?>
            </div>

            <div class="form-group">
                <div class="peers ai-c jc-sb fxw-nw">
                    <div class="peer">
                    </div>
                    <div class="peer">
                        <a
                            href="/"
                            class="btn btn-primary">
                            <?= __('Start') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End certificates login -->


        <div class="float-right" style="padding-top: 100px;">
            <a href="https://openitcockpit.io/" target="_blank" class="btn btn-sm btn-light btn-icon">
                <i class="fa-solid fa-earth-americas"></i>
            </a>
            <a href="https://github.com/it-novum/openITCOCKPIT" target="_blank"
               class="btn btn-sm btn-light btn-icon">
                <i class="fa-brands fa-github"></i>
            </a>
            <a href="https://twitter.com/openITCOCKPIT" target="_blank" class="btn btn-sm btn-light btn-icon">
                <i class="fa-brands fa-x-twitter"></i>
            </a>
            <a href="https://www.reddit.com/r/openitcockpit" target="_blank" class="btn btn-sm btn-light btn-icon">
                <i class="fa-brands fa-reddit"></i>
            </a>
            <a href="https://discord.gg/G8KhxKuQ9G" target="_blank" class="btn btn-sm btn-light btn-icon">
                <i class="fa-brands fa-discord"></i>
            </a>
        </div>
    </div>
</div>
