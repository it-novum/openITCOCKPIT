<?php
/**
 * @var $RepositoryChecker \itnovum\openITCOCKPIT\Core\RepositoryChecker
 * @var $DnfRepositoryChecker \itnovum\openITCOCKPIT\Core\DnfRepositoryChecker
 * @var $LsbRelease \itnovum\openITCOCKPIT\Core\System\Health\LsbRelease
 */

$isOldRepositoryInUse = false;
$hasError = false;

try {
    $isOldRepositoryInUse = $RepositoryChecker->isOldRepositoryInUse();
} catch (\Exception $e) {
    $hasError = true;
}

if (($isOldRepositoryInUse === true || $hasError === true) && $LsbRelease->isDebianBased()): ?>
    <div>
        <div class="alert alert-danger alert-block">
            <a href="javascript:void(0);" data-dismiss="alert" class="close">×</a>
            <h5 class="alert-heading"><i class="fa fa-warning"></i>
                <?php echo __('APT Repository - Manually action required!'); ?>
            </h5>
            <p>
                <?php echo __('Please contact your system administrator.'); ?>
            </p>
            <p>
                <?php
                try {
                    $RepositoryChecker->exists();

                } catch (Exception $e) {
                    printf('<b>%s:</b><br />', __('Error code'));
                    printf('<code>%s</code>', $e->getMessage());
                }
                ?>
            </p>
            <p>
                <?php
                try {
                    $RepositoryChecker->isReadable();

                } catch (Exception $e) {
                    printf('<b>%s:</b><br />', __('Error code'));
                    printf('<code>%s</code>', $e->getMessage());
                    printf('<br />Try <br /><code>chmod 644 %s</code>', $RepositoryChecker->getSourcesList());
                }
                ?>
            </p>
            <?php
            try {
                $RepositoryChecker->isOldRepositoryInUse();
                ?>
                <p>
                    <?php echo __('Your system is using the old openITCOCKPIT APT repository, which will be shut down soon.'); ?>
                    <br/>
                    <?php echo __('Please update your sources.list to use our new repository'); ?>
                    <br/>
                    <code>apt-get update</code>
                    <br/>
                    <code>apt-get install openitcockpit-release</code>
                    <br/>
                    <code>dpkg-reconfigure openitcockpit-release</code>

                </p>
                <?php
            } catch (\Exception $e) {
                printf('<b>%s:</b><br />', __('Error code'));
                printf('<code>%s</code>', $e->getMessage());
            }
            ?>
        </div>
    </div>
<?php endif;

// Checking DNF Repo on RHEL

$hasError = false;
try {
    $hasError = $DnfRepositoryChecker->hasError();
} catch (\Exception $e) {
    $hasError = true;
}

if ($hasError && $LsbRelease->isRhelBased()):?>
    <div>
        <div class="alert alert-danger alert-block">
            <a href="javascript:void(0);" data-dismiss="alert" class="close">×</a>
            <h5 class="alert-heading"><i class="fa fa-warning"></i>
                <?php echo __('DNF Repository - Manually action required!'); ?>
            </h5>
            <p>
                <?php echo __('Please contact your system administrator.'); ?>
            </p>
            <p>
                <?php
                try {
                    $DnfRepositoryChecker->exists();

                } catch (Exception $e) {
                    printf('<b>%s:</b><br />', __('Error code'));
                    printf('<code>%s</code>', $e->getMessage());
                }
                ?>
            </p>
            <p>
                <?php
                try {
                    $DnfRepositoryChecker->isReadable();

                } catch (Exception $e) {
                    printf('<b>%s:</b><br />', __('Error code'));
                    printf('<code>%s</code>', $e->getMessage());
                    printf('<br />Try <br /><code>chmod 644 %s</code>', $DnfRepositoryChecker->getRepoConfig());
                }
                ?>
            </p>
        </div>
    </div>
<?php endif;
