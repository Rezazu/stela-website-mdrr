<?PHP
/**
 * Get all bidders for an item
 *
 * $Id: GetAllBidders.php,v 1.1 2004/10/28 17:14:53 schst Exp $
 *
 * @package Services_Ebay
 * @author  Stephan Schmidt <schst@php.net>
 * @link    http://developer.ebay.com/DevZone/docs/API_Doc/Functions/GetAllBidders/GetAllBiddersLogic.htm
 * @todo    create a model for the result set
 */
class Services_Ebay_Call_GetAllBidders extends Services_Ebay_Call 
{
   /**
    * verb of the API call
    *
    * @var  string
    */
    protected $verb = 'GetAllBidders';

   /**
    * parameter map that is used, when scalar parameters are passed
    *
    * @var  array
    */
    protected $paramMap = array(
                                 'ItemId',
                                 'SecondChanceEnabledOnly',
                                 'ViewAllBidders'
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
        return $return['GetAllBiddersResult'];
    }
}
?>