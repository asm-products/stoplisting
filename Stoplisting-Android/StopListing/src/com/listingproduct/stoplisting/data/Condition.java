package com.listingproduct.stoplisting.data;

/**
 * Represents the condition type of swank search
 * @author Xiao mingming
 */
public enum Condition {
	USED(""),//USED("&condition=3000") : default value
	NEW("&condition=1000"), 
	BOTH("&condition=1");
	
	String strUrlElement;
	
	Condition(String urlElement) {
		strUrlElement = urlElement;
	}
	
	public String getUrlElement() {
		return strUrlElement;
	}
}
