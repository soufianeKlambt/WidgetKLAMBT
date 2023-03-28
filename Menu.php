<?php
namespace Piwik\Plugins\WidgetKLAMBT;

use Piwik\Common;
use Piwik\Menu\MenuTop;
use Piwik\Piwik;
use Piwik\Plugins\UsersManager\UserPreferences;
use Piwik\Site;

/**
 */
class Menu extends \Piwik\Plugin\Menu
{
  public function configureTopMenu(MenuTop $menu)
  {
    $userPreferences = new UserPreferences();
    $idSite = $userPreferences->getDefaultWebsiteId();
    $idSite = Common::getRequestVar('idSite', $idSite, 'int');

    $tooltip = Piwik::translate('Dashboard_TopLinkTooltip', Site::getNameFor($idSite));

    $urlParams = $this->urlForModuleActionWithDefaultUserParams('CoreHome', 'index', ['idSite' => $idSite]) ;
    $menu->addItem('Dashboard_Dashboard', null, $urlParams, 1, $tooltip);
  }
}
