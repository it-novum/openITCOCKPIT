<div id="particles-js" class="peer peer-greed h-100 pos-r">
    <!-- layout fix -->
</div>


<div class="col-12 col-md-4 peer pX-40 pY-80 h-100 scrollable pos-r login-side-bg" style='min-width: 320px;'>

    <div class="col-12">
        <img class="img-fluid" src="/img/logos/openITCOCKPIT_Logo_Big.png"/>
    </div>

    <h4 class="fw-300 c-white mB-40"><?= __('Login') ?></h4>
    <form>
        <div class="form-group">
            <label class="text-normal c-white"><?= __('Username') ?></label>
            <input type="email" class="form-control" placeholder="John Doe">
        </div>
        <div class="form-group">
            <label class="text-normal c-white"><?= __('Password') ?></label>
            <input type="password" class="form-control" placeholder="Password">
        </div>
        <div class="form-group">
            <div class="peers ai-c jc-sb fxw-nw">
                <div class="peer">
                    <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                        <input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
                        <label for="inputCall1" class=" peers peer-greed js-sb ai-c">
                            <span class="peer peer-greed"><?= __('Remember Me') ?></span>
                        </label>
                    </div>
                </div>
                <div class="peer">
                    <button class="btn btn-primary"><?= __('Login') ?></button>
                </div>
            </div>
        </div>
    </form>

    <div class="float-right" style="padding-top: 100px;">
        <a href="https://openitcockpit.io/" target="_blank" class="btn btn-sm btn-light btn-icon">
            <i class="fa fa-lg fa-globe"></i>
        </a>
        <a href="https://github.com/it-novum/openITCOCKPIT" target="_blank"
           class="btn btn-sm btn-light btn-icon">
            <i class="fab fa-lg fa-github"></i>
        </a>
        <a href="https://twitter.com/openITCOCKPIT" target="_blank" class="btn btn-sm btn-light btn-icon">
            <i class="fab fa-lg fa-twitter"></i>
        </a>
        <a href="https://www.reddit.com/r/openitcockpit" target="_blank" class="btn btn-sm btn-light btn-icon">
            <i class="fab fa-lg fa-reddit"></i>
        </a>
    </div>

</div>
