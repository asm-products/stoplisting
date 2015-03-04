<?php
// G-zip Compression	
ini_set('zlib.output_compression', 'On');  
ini_set('zlib.output_compression_level', '1');
require_once ('../../dash/ebay_trading/tradingConstants.php');
class ebay{
    //stores already queried keyword suggestions for this session
    private $keywordSuggestions=array();
    
	//variable instantiation
    private $uri_finding = " ";
    private $appid = " ";
	private $version="1.0.0";
    private $format = "JSON";
    private $ruName = " ";
	
       /**
    * Constructor
    *
    * Sets the eBay version to the current API version
    * 
    */
    public function __construct(){
        //$this->version = $this->getCurrentVersion();
    }
    
    /**
    * Get Current Version
    *
    * Returns a string of the current eBay Finding API version
    * 
    */
    private function getCurrentVersion(){
        $uri = sprintf("%s?OPERATION-NAME=getVersion&SECURITY-APPNAME=%s&RESPONSE-DATA-FORMAT=%s",
                       $this->uri_finding,
                       $this->appid,
                       $this->format);
        $response = $this->curl($uri);
        return json_decode($response->getVersionResponse[0]->version[0]);
    } 
    /**
    * Find Products
    *
    * Allows you to search for eBay products based on keyword, product id or
    * keywords (default).  Available values for search_type include
    * findItemsByKeywords, findItemsByCategory, and findItemsByProduct
    * 
    */
    public function findProduct($search_type = 'findItemsByKeywords', $search_value = '10181', $entries_per_page = 3, $condition = NULL, $barcodeType = NULL, $listingType = NULL){
	if (is_null($barcodeType)) {
		// keyword search - also searches for exact search '@' mechanic
		if((substr($search_value, 0, 1)) !== "@") {
                $sv=$search_value;
                if (!empty($this->keywordSuggestions[$sv])) {
                    $search_value=$this->keywordSuggestions[$sv];
                } else {
                    $result2 = $this->getKeywordRecommendations(urlencode(utf8_encode($search_value)));
                    if (!empty($result2['getSearchKeywordsRecommendationResponse'][0]['keywords'][0])) {
                        $search_value = $result2['getSearchKeywordsRecommendationResponse'][0]['keywords'][0];
                    }
                    $this->keywordSuggestions[$sv] = $search_value;
                }
		} else {$search_value = substr($search_value, 1); }
		
            //$category_id = $this->getCategorySuggestions(urlencode(utf8_encode($search_value)));
		if (!empty($category_id)) {
	   		$category_suggestions = "&categoryid=".$category_id;
	   	} else {
	   		$category_suggestions = "";
	   	}
	} else if((substr($search_value, 0, 1)) == "@"){
		 $search_value = substr($search_value, 1); 
	} 
   	
        //determine how to structure the search query parameter based on search type
        $search_value = urlencode(utf8_encode($search_value));
        $search_field = "";
        switch ($search_type){
            case 'findItemsByCategory': $search_field = "categoryId=$search_value";
                                        break;
            case 'findItemsByProduct':  $search_field = "productId.@type=$barcodeType&productId=$search_value";
                                        break;
            case 'findItemsByKeywords':
            default:                    $search_field = "keywords=" . $search_value;
                                        break;
        }
        
        
        //Listing Type Setup
	
	if (is_null($barcodeType)) {$i=1;} else {$i=0;}
	if (is_null($listingType)) {
		$listingType  = "&itemFilter(".$i.").name=ListingType";
		$listingType .= "&itemFilter(".$i.").value(0)=FixedPrice";
		$listingType .= "&itemFilter(".$i.").value(1)=AuctionWithBIN";
	}
	else if ($listingType == "AO") { // if auction only
		$listingType  = "&itemFilter(".$i.").name=ListingType";
		$listingType .= "&itemFilter(".$i.").value(0)=Auction";
	}
	else if ($listingType == "ALL") {// if both 
		$listingType  = "&itemFilter(".$i.").name=ListingType";
		$listingType .= "&itemFilter(".$i.").value(0)=FixedPrice";
		$listingType .= "&itemFilter(".$i.").value(1)=AuctionWithBIN";
		$listingType .= "&itemFilter(".$i.").value(2)=Auction";
	}
        
        //Condition Type Setup
        if (empty($condition)) {
       		$condition = 3000;
	}
	if($condition == 3000){
		// if condition is used, add all used conditions 
		//3000 used, 2000 man refurb, 2500 seller refurb , 4000 very good, 5000 good, 6000 acceptable
		if (is_null($barcodeType)) {$i=2;} else {$i=1;}
		$condition .= "&itemFilter(".$i.").value(1)=2000";
		$condition .= "&itemFilter(".$i.").value(2)=2500";
		$condition .= "&itemFilter(".$i.").value(3)=4000";
		$condition .= "&itemFilter(".$i.").value(4)=5000";
		$condition .= "&itemFilter(".$i.").value(5)=6000";
	} else if($condition == 1000) {
		// if condition is new, add new conditions also
		//1000 new, 1500 new other, 1750 new with defects
		if (is_null($barcodeType)) {$i=2;} else {$i=1;}
		$condition .= "&itemFilter(".$i.").value(1)=1500";
		$condition .= "&itemFilter(".$i.").value(2)=1750";
	} else if($condition == 1) { 
		//all listings
		if (is_null($barcodeType)) {$i=2;} else {$i=1;}
		$condition  = "";
		$condition .= "&itemFilter(".$i.").value(0)=1000";
		$condition .= "&itemFilter(".$i.").value(1)=1500";
		$condition .= "&itemFilter(".$i.").value(2)=1750";
		$condition .= "&itemFilter(".$i.").value(3)=2000";
		$condition .= "&itemFilter(".$i.").value(4)=2500";
		$condition .= "&itemFilter(".$i.").value(5)=3000";
		$condition .= "&itemFilter(".$i.").value(6)=4000";
		$condition .= "&itemFilter(".$i.").value(7)=5000";
		$condition .= "&itemFilter(".$i.").value(8)=6000";
	}
	
	
	
	
   
        //build query uri
        
	if (is_null($barcodeType)) {
        $uri = sprintf("%s?OPERATION-NAME=%s&SERVICE-VERSION=%s&SECURITY-APPNAME=%s&RESPONSE-DATA-FORMAT=%s&REST-PAYLOAD&%s%s&paginationInput.entriesPerPage=%s&itemFilter(0).name=SoldItemsOnly&itemFilter(0).value=true%s&itemFilter(2).name(0)=Condition&itemFilter(2).value(0)=%s&sortOrder=EndTimeSoonest",
                        $this->uri_finding,
                        $search_type,
                        $this->version,
                        $this->appid,
                        $this->format,
                        $search_field,
                        $category_suggestions,
                        $entries_per_page,
                        $listingType,
                        $condition); 
        } else {                              
    	$uri = sprintf("%s?OPERATION-NAME=%s&SERVICE-VERSION=%s&SECURITY-APPNAME=%s&RESPONSE-DATA-FORMAT=%s&REST-PAYLOAD&%s%s&paginationInput.entriesPerPage=%s%s&itemFilter(1).name(0)=Condition&itemFilter(1).value(0)=%s&sortOrder=EndTimeSoonest",
                        $this->uri_finding,
                        $search_type,
                        $this->version,
                        $this->appid,
                        $this->format,
                        $search_field,
                        $category_suggestions,
                        $entries_per_page,
                        $listingType,
                        $condition);
        }   
       		//return $uri;
       		return  json_decode($this->curl($uri),true);
     
    }
    
    /**
    * Get Histograms
    *
    * Obtains histogram data about a provided category id
    * 
    */
    public function getHistograms($cat = '63861'){
        $uri = sprintf("%s?OPERATION-NAME=getHistograms&SERVICE-VERSION=%s&SECURITY-APPNAME=%s&RESPONSE-DATA-FORMAT=%s&REST-PAYLOAD&categoryId=%s",
                       $this->uri_finding,
                       $this->version,
                       $this->appid,
                       $this->format,
                       $cat);
        
        return json_decode($this->curl($uri));
    }
    
    /**
    * Get keyword recommendations
    *
    * Returns a series of common keyword recommendations for a search keyword.
    * This is useful when an incorrect search term is provided.
    * 
    */
    public function getKeywordRecommendations($keywords){
        $uri = sprintf("%s?OPERATION-NAME=getSearchKeywordsRecommendation&SERVICE-VERSION=%s&SECURITY-APPNAME=%s&RESPONSE-DATA-FORMAT=%s&REST-PAYLOAD&keywords=%s",
                       $this->uri_finding,
                       $this->version,
                       $this->appid,
                       $this->format,
                       $keywords);
       return json_decode($this->curl($uri),true);
    }

    /**
    * cURL
    *
    * Standard cURL function to run GET & POST requests
    * 
    */
    
    private function curl($url, $method = 'GET', $headers = null, $postvals = null){ 	
	        $ch = curl_init($url);

        if ($method == 'GET'){
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        }  
        else {
            $options = array(
                CURLOPT_HEADER 		=> false,
                CURLINFO_HEADER_OUT 	=> true,
                CURLOPT_VERBOSE 	=> true,
                CURLOPT_HTTPHEADER 	=> $headers,
                CURLOPT_RETURNTRANSFER 	=> true,
                CURLOPT_POST 		=> true,
                CURLOPT_POSTFIELDS 	=> $postvals,
                CURLOPT_CUSTOMREQUEST 	=> $method,
                CURLOPT_TIMEOUT 	=> 3
                
            );
            curl_setopt_array($ch, $options);
        }
        $response = curl_exec($ch);
        curl_close($ch);  
        return $response;
    }
    
    
	public function getCategorySuggestions($keywords){
		// create the XML request
		
		$xmlRequest  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
		$xmlRequest .= "<GetSuggestedCategoriesRequest xmlns=\"urn:ebay:apis:eBLBaseComponents\">";
		$xmlRequest .= "<RequesterCredentials>";
		$xmlRequest .= "<eBayAuthToken>" . AUTH_TOKEN . "</eBayAuthToken>";
		$xmlRequest .= "</RequesterCredentials>";
		$xmlRequest .= "<Query>".$keywords."</Query>";
		$xmlRequest .= "<ErrorLanguage>en_US</ErrorLanguage>";
		$xmlRequest .= "<WarningLevel>High</WarningLevel>";
		$xmlRequest .= "</GetSuggestedCategoriesRequest>";
		
		// define our header array for the Trading API call
		// notice different headers from shopping API and SITE_ID changes to SITEID
		$headers = array(
			'X-EBAY-API-SITEID:'.SITEID,
			'X-EBAY-API-CALL-NAME:GetSuggestedCategories',
			'X-EBAY-API-REQUEST-ENCODING:XML',
			'X-EBAY-API-COMPATIBILITY-LEVEL:' . API_COMPATIBILITY_LEVEL,
			'X-EBAY-API-DEV-NAME:' . API_DEV_NAME,
			'X-EBAY-API-APP-NAME:' . API_APP_NAME,
			'X-EBAY-API-CERT-NAME:' . API_CERT_NAME,
			'Content-Type: text/xml;charset=utf-8'
		);
		
		$response=$this->curl(API_URL, "POST", $headers, $xmlRequest);
		// Verify that the xml response object was created
	
		$xmlResponse = simplexml_load_string($response);
	
		// Verify that the xml response object was created
		if ($xmlResponse) {
            if ($xmlResponse->Ack == "Success" && $xmlResponse->CategoryCount>0) {
				return $xmlResponse->SuggestedCategoryArray->SuggestedCategory[0]->Category->CategoryID->__toString();
			}
		}
   	 }
    
	    
	public function getPhoto($itemid, $getalldata = NULL){
		$xmlRequest  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
		$xmlRequest .= "<GetItemRequest xmlns=\"urn:ebay:apis:eBLBaseComponents\">";
		$xmlRequest .= "<DetailLevel>ItemReturnDescription</DetailLevel>";
		if (!is_null($getalldata)) {
			$xmlRequest .= "<IncludeItemSpecifics>true</IncludeItemSpecifics>";
		}
		$xmlRequest .= "<RequesterCredentials>";
		$xmlRequest .= "<eBayAuthToken>" . AUTH_TOKEN . "</eBayAuthToken>";
		$xmlRequest .= "</RequesterCredentials>";
		$xmlRequest .= "<ItemID>". $itemid ."</ItemID>";
		$xmlRequest .= "<ErrorLanguage>en_US</ErrorLanguage>";
		$xmlRequest .= "<WarningLevel>High</WarningLevel>";
		$xmlRequest .= "</GetItemRequest>";

		// define our header array for the Trading API call
		// notice different headers from shopping API and SITE_ID changes to SITEID
		$headers = array(
			'X-EBAY-API-SITEID:'.SITEID,
			'X-EBAY-API-CALL-NAME:GetItem',
			'X-EBAY-API-REQUEST-ENCODING:XML',
			'X-EBAY-API-COMPATIBILITY-LEVEL:' . API_COMPATIBILITY_LEVEL,
			'X-EBAY-API-DEV-NAME:' . API_DEV_NAME,
			'X-EBAY-API-APP-NAME:' . API_APP_NAME,
			'X-EBAY-API-CERT-NAME:' . API_CERT_NAME,
			'Content-Type: text/xml;charset=utf-8'
		);
		
		$response	= $this->curl(API_URL, "POST", $headers, $xmlRequest);
		$xmlResponse 	= simplexml_load_string($response);
		// Verify that the xml response object was created
		if ($xmlResponse) {
			if ($xmlResponse->Ack == "Success") { 
				if (is_null($getalldata)) {
					return $xmlResponse->Item->PictureDetails->GalleryURL->__toString();
				} else {
				//return $xmlResponse->Item;
					return json_encode(array(
						'Title' 		=> $xmlResponse->Item->Title->__toString(),
						
						'ListingType'		=> $xmlResponse->Item->ListingType->__toString(),
						'ConditionID'		=> $xmlResponse->Item->ConditionID->__toString(),
						'ShippingService' 	=> $xmlResponse->Item->ShippingDetails->ShippingServiceOptions->ShippingService->__toString(),
						'ShippingServiceCost' 	=> $xmlResponse->Item->ShippingDetails->ShippingServiceOptions->ShippingServiceCost->__toString(),
						'FreeShipping' 		=> $xmlResponse->Item->ShippingDetails->ShippingServiceOptions->ShippingServiceCost->__toString(),
						'SellerID' 		=> $xmlResponse->Item->Seller->UserID->__toString(), 
						'Description' 		=> preg_replace('/\s\s+/', ' ', strip_tags($this->strip_html_tags($xmlResponse->Item->Description->__toString()))),
						'CategoryId' 		=> $xmlResponse->Item->PrimaryCategory->CategoryID->__toString(),
						'CategoryName' 		=> $xmlResponse->Item->PrimaryCategory->CategoryName->__toString(),
						'Price' 		=> max($xmlResponse->Item->SellingStatus->CurrentPrice->__toString(), $xmlResponse->Item->ListingDetails->ConvertedBuyItNowPrice->__toString()),
						
						'PictureDetails'	=> $xmlResponse->Item->PictureDetails->GalleryURL->__toString(),
						'HandlingTime'		=> $xmlResponse->Item->DispatchTimeMax->__toString(),
						'ItemSpecifics' 	=> $xmlResponse->Item->ItemSpecifics
					));
				}
            }
        }
    }


    /**
     * Retrieves picture urls for multiple item ids given as array using only one api request.
     *
     * @param array $itemids an array of item ids
     * @return array an associative array containing picture urls for each item id (itemid=>pictureurl)
     */
    public function getPhotosLite($itemids){
        $xmlRequest  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
        $xmlRequest .= "<GetMultipleItemsRequest xmlns=\"urn:ebay:apis:eBLBaseComponents\">";
        foreach ($itemids as $itemid) {
            $xmlRequest .= "<ItemID>". $itemid ."</ItemID>";
        }
        $xmlRequest .= "</GetMultipleItemsRequest>";

        // define our header array for the Trading API call
        // notice different headers from shopping API and SITE_ID changes to SITEID
        $headers = array(
            'X-EBAY-API-SITEID:'.SITEID,
            'X-EBAY-API-CALL-NAME:GetMultipleItems',
            'X-EBAY-API-REQUEST-ENCODING:XML',
            'X-EBAY-API-COMPATIBILITY-LEVEL:' . API_COMPATIBILITY_LEVEL,
            'X-EBAY-API-DEV-NAME:' . API_DEV_NAME,
            'X-EBAY-API-APP-NAME:' . API_APP_NAME,
            'X-EBAY-API-CERT-NAME:' . API_CERT_NAME,
            'Content-Type: text/xml;charset=utf-8'
        );

        $response	= $this->curl("http://open.api.ebay.com/shopping?&version=525", "POST", $headers, $xmlRequest);
        $xmlResponse 	= simplexml_load_string($response);
        // Verify that the xml response object was created
        if ($xmlResponse) {
            if ($xmlResponse->Ack == "Success") {
                $result=array();

                foreach($xmlResponse->Item as $item) {
                    $result[$item->ItemID->__toString()]=$item->PictureURL->__toString();
                }
                //return $xmlResponse->Item->__toString();
                return $result;
			}
		}
	}
	
	public function getPhotoLite($itemid){
		$xmlRequest  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
		$xmlRequest .= "<GetSingleItemRequest xmlns=\"urn:ebay:apis:eBLBaseComponents\">";
		$xmlRequest .= "<ItemID>". $itemid ."</ItemID>";
		$xmlRequest .= "</GetSingleItemRequest>";

		// define our header array for the Trading API call
		// notice different headers from shopping API and SITE_ID changes to SITEID
		$headers = array(
			'X-EBAY-API-SITEID:'.SITEID,
			'X-EBAY-API-CALL-NAME:GetSingleItem',
			'X-EBAY-API-REQUEST-ENCODING:XML',
			'X-EBAY-API-COMPATIBILITY-LEVEL:' . API_COMPATIBILITY_LEVEL,
			'X-EBAY-API-DEV-NAME:' . API_DEV_NAME,
			'X-EBAY-API-APP-NAME:' . API_APP_NAME,
			'X-EBAY-API-CERT-NAME:' . API_CERT_NAME,
			'Content-Type: text/xml;charset=utf-8'
		);
		
		$response	= $this->curl("http://open.api.ebay.com/shopping?&version=515", "POST", $headers, $xmlRequest);
		$xmlResponse 	= simplexml_load_string($response);
		// Verify that the xml response object was created
		if ($xmlResponse) {
			if ($xmlResponse->Ack == "Success") { 
			
					//return $xmlResponse->Item->__toString();
					return $xmlResponse->Item->PictureURL->__toString();
			}
		}
	}
	
	
	
	public function getEbaySessionID() {
		$xmlRequest  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
		$xmlRequest .= "<GetSessionIDRequest xmlns=\"urn:ebay:apis:eBLBaseComponents\">";
		$xmlRequest .= "<RuName>".$this->ruName."</RuName>";	
		$xmlRequest .= "<ErrorLanguage>en_US</ErrorLanguage>";
		$xmlRequest .= "<WarningLevel>High</WarningLevel>";
		$xmlRequest .= "</GetSessionIDRequest>";

		// define our header array for the Trading API call
		// notice different headers from shopping API and SITE_ID changes to SITEID
		$headers = array(
			'X-EBAY-API-SITEID:'.SITEID,
			'X-EBAY-API-CALL-NAME:GetSessionID',
			'X-EBAY-API-REQUEST-ENCODING:XML',
			'X-EBAY-API-COMPATIBILITY-LEVEL:' . API_COMPATIBILITY_LEVEL,
			'X-EBAY-API-DEV-NAME:' . API_DEV_NAME,
			'X-EBAY-API-APP-NAME:' . API_APP_NAME,
			'X-EBAY-API-CERT-NAME:' . API_CERT_NAME,
			'Content-Type: text/xml;charset=utf-8'
		);
		
		$response	= $this->curl(API_URL, "POST", $headers, $xmlRequest);
		$xmlResponse 	= simplexml_load_string($response);
		// Verify that the xml response object was created
		if ($xmlResponse) {
			if ($xmlResponse->Ack == "Success") { 
				return "https://signin.ebay.com/ws/eBayISAPI.dll?SignIn&RUName=".$this->ruName."&SessID=".$xmlResponse->SessionID->__toString();
			}
			return NULL;
		}
	}
	
		public function fetchToken($session_id) {
		$xmlRequest  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
		$xmlRequest .= "<FetchTokenRequest xmlns=\"urn:ebay:apis:eBLBaseComponents\">";
		$xmlRequest .= "<SessionID>".$session_id."</SessionID>";	
		$xmlRequest .= "<ErrorLanguage>en_US</ErrorLanguage>";
		$xmlRequest .= "<WarningLevel>High</WarningLevel>";
		$xmlRequest .= "</FetchTokenRequest>";

		// define our header array for the Trading API call
		// notice different headers from shopping API and SITE_ID changes to SITEID
		$headers = array(
			'X-EBAY-API-SITEID:'.SITEID,
			'X-EBAY-API-CALL-NAME:FetchToken',
			'X-EBAY-API-REQUEST-ENCODING:XML',
			'X-EBAY-API-COMPATIBILITY-LEVEL:' . API_COMPATIBILITY_LEVEL,
			'X-EBAY-API-DEV-NAME:' . API_DEV_NAME,
			'X-EBAY-API-APP-NAME:' . API_APP_NAME,
			'X-EBAY-API-CERT-NAME:' . API_CERT_NAME,
			'Content-Type: text/xml;charset=utf-8'
		);
		
		$response	= $this->curl(API_URL, "POST", $headers, $xmlRequest);
		$xmlResponse 	= simplexml_load_string($response);
		// Verify that the xml response object was created
		if ($xmlResponse) {
			if ($xmlResponse->Ack == "Success") { 
				return $xmlResponse->eBayAuthToken->__toString();
			}
			return NULL;
		}
	}
	#http://nadeausoftware.com/articles/2007/09/php_tip_how_strip_html_tags_web_page
private function strip_html_tags( $text )
{
    $text = preg_replace(
        array(
          // Remove invisible content
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
          // Add line breaks before and after blocks
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',
        ),
        array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
            "\n\$0", "\n\$0",
        ),
        $text );
    return strip_tags( $text );
}
}
?>