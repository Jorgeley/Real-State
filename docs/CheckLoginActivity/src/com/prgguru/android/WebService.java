package com.prgguru.android;

import org.ksoap2.SoapEnvelope;
import org.ksoap2.serialization.PropertyInfo;
import org.ksoap2.serialization.SoapObject;
import org.ksoap2.serialization.SoapPrimitive;
import org.ksoap2.serialization.SoapSerializationEnvelope;
import org.ksoap2.transport.HttpTransportSE;

public class WebService {
	//Namespace of the Webservice - can be found in WSDL
	private static String NAMESPACE = "http://service.programmerguru.com/";
	//Webservice URL - WSDL File location	
	private static String URL = "http://192.168.2.2:8080/LoginWebService/LoginWebService?WSDL";//Make sure you changed IP address
	//SOAP Action URI again Namespace + Web method name
	private static String SOAP_ACTION = "http://service.programmerguru.com/";
	
	public static boolean invokeLoginWS(String userName,String passWord, String webMethName) {
		boolean loginStatus = false;
		// Create request
		SoapObject request = new SoapObject(NAMESPACE, webMethName);
		// Property which holds input parameters
		PropertyInfo unamePI = new PropertyInfo();
		PropertyInfo passPI = new PropertyInfo();
		// Set Username
		unamePI.setName("username");
		// Set Value
		unamePI.setValue(userName);
		// Set dataType
		unamePI.setType(String.class);
		// Add the property to request object
		request.addProperty(unamePI);
		//Set Password
		passPI.setName("password");
		//Set dataType
		passPI.setValue(passWord);
		//Set dataType
		passPI.setType(String.class);
		//Add the property to request object
		request.addProperty(passPI);
		// Create envelope
		SoapSerializationEnvelope envelope = new SoapSerializationEnvelope(
				SoapEnvelope.VER11);
		// Set output SOAP object
		envelope.setOutputSoapObject(request);
		// Create HTTP call object
		HttpTransportSE androidHttpTransport = new HttpTransportSE(URL);

		try {
			// Invoke web service
			androidHttpTransport.call(SOAP_ACTION+webMethName, envelope);
			// Get the response
			SoapPrimitive response = (SoapPrimitive) envelope.getResponse();
			// Assign it to  boolean variable variable
			loginStatus = Boolean.parseBoolean(response.toString());

		} catch (Exception e) {
			//Assign Error Status true in static variable 'errored'
			CheckLoginActivity.errored = true;
			e.printStackTrace();
		} 
		//Return booleam to calling object
		return loginStatus;
	}
}
