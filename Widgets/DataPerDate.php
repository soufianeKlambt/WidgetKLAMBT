<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */

namespace Piwik\Plugins\WidgetKLAMBT\Widgets;

use Piwik\Plugins\WidgetKLAMBT\WKCache;
use Piwik\Widget\Widget;
use Piwik\Widget\WidgetConfig;
use Piwik\API\Request;
use Piwik\Common;
use Piwik\Piwik;
class DataPerDate extends Widget {
  public static function configure(WidgetConfig $config)
  {
    $config->setParameters(array('embed' => '1'));
    $config->setIsNotWidgetizable();
    $config->setOrder(20);
    $config->setIsEnabled(!Piwik::isUserIsAnonymous());
  }


  /**
   * This method renders the widget. It's on you how to generate the content of
   * the widget. As long as you return a string everything is fine. You can use
   * for instance a "Piwik\View" to render a twig template. In such a case
   * don't forget to create a twig template (eg. myViewTemplate.twig) in the
   * "templates" directory of your plugin.
   *
   * @return string
   */
  public function render() {
    $module = Piwik::getModule();
    $action = Piwik::getAction();
    if (Common::getRequestVar('token_auth', '', 'string') !== ''
      && Request::shouldReloadAuthUsingTokenAuth(null)
    ) {
      Request::reloadAuthUsingTokenAuth();
      Request::checkTokenAuthIsNotLimited($module, $action);
    }
    $idSite = $_GET['idSite'];
    $date = $_GET['date'];
    $condition = '="'.$date.'"';
    if(isset($_GET['dateto']) && $_GET['dateto'] != ''){
      $condition = ' between "'.$date.'" and "'.$_GET['dateto'].'"';
    }
      $limit = '';
    if(isset($_GET['limit']) && $_GET['limit'] != ''){
        $limit = ' limit '.$_GET['limit'];
    }
    $sql = 'SELECT pageimpressions,unique_pageimpressions,unique_visitors,url,title FROM klambt_day_data where datum '.$condition.' and site_id='.$idSite.' '.$limit;

    $cache=new WKCache();
    $result= $cache->getCacheData('DataPerDate-'.$idSite,$sql);
   return json_encode($result,TRUE);

  }


}
