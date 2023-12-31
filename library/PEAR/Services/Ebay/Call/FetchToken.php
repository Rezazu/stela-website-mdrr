<?PHP
/**
 * Fetch a token from eBay.
 *
 * This method is only needed, when using Services_Ebay in a 
 * non-web environment
 *
 * $Id: FetchToken.php,v 1.1 2004/10/28 17:14:53 schst Exp $
 *
 * @package Services_Ebay
 * @author  Stephan Schmidt <schst@php.net>
 * @link    http://developer.ebay.com/DevZone/docs/API_Doc/Functions/FetchToken/FetchTokenLogic.htm
 */
class Services_Ebay_Call_FetchToken extends Services_Ebay_Call 
{
   /**
    * verb of the API call
    *
    * @var  string
    */
    protected $verb = 'FetchToken';

   /**
    * authentication type of the call
    *
    * @var  int
    */
    protected $authType = Services_Ebay::AUTH_TYPE_NONE;

    /**
    * parameter map that is used, when scalar parameters are passed
    *
    * @var  array
    */
    protected $paramMap = array(
                                 'SecretId'
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
        return $return['FetchTokenResult'];
    }
}
?>