<?PHP
/**
 * Get category 2 cs
 *
 * $Id: GetCategory2CS.php,v 1.1 2005/01/04 20:10:18 schst Exp $
 *
 * @package Services_Ebay
 * @author  Stephan Schmidt <schst@php.net>
 * @link    http://developer.ebay.com/DevZone/docs/API_Doc/Functions/GetCategory2CS/GetCategory2CSLogic.htm
 *
 * @todo    finish this API call
 * @todo    build a model for this
 */
class Services_Ebay_Call_GetCategory2CS extends Services_Ebay_Call 
{
   /**
    * verb of the API call
    *
    * @var  string
    */
    protected $verb = 'GetCategory2CS';

   /**
    * parameter map that is used, when scalar parameters are passed
    *
    * @var  array
    */
    protected $paramMap = array(
                                 'CategoryId',
                                );
    
   /**
    * arguments of the call
    *
    * @var  array
    */
    protected $args = array(
                            'DetailLevel' => 1
                        );

    protected $unserializerOptions = array(
                                            'keyAttribute' => array('Category' => 'id')
                                        );

   /**
    * make the API call
    *
    * @param    object Services_Ebay_Session
    * @return   string
    */
    public function call(Services_Ebay_Session $session)
    {
        $return = parent::call($session);
        return $return['Category2CS'];
    }
}
?>