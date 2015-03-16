package com.listingproduct.stoplisting.data;

/**
 * Represents the Listing type of swank search
 * @author Xiao mingming
 */
public enum ListingType {
	AUCTION("&listingType=AO"),
	BIN(""),	// BIN("&listingType=BIN") : default value
	BOTH("&listingType=ALL");
	
	String strUrlElement;
	
	ListingType(String urlElement) {
		strUrlElement = urlElement;
	}
	
	public String getUrlElement() {
		return strUrlElement;
	}
}
