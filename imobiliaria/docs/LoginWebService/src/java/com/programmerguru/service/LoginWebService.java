/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.programmerguru.service;

import javax.jws.WebService;
import javax.jws.WebMethod;
import javax.jws.WebParam;

/**
 *
 * @author Udhay
 */
@WebService(serviceName = "LoginWebService")
public class LoginWebService {

    /** This is a sample web service operation */
    @WebMethod(operationName = "hello")
    public String hello(@WebParam(name = "name") String txt) {
        return "Hello " + txt + " !";
    }

    /**
     * Web service operation
     */
    @WebMethod(operationName = "authenticateUser")
    public boolean authenticateUser(@WebParam(name = "username") String username, @WebParam(name = "password") String password) {
        //TODO write your implementation code here:
        boolean result = false;
        if(username.equalsIgnoreCase("admin") && password.equalsIgnoreCase("pass")){
            result = true;
        }
        System.out.println("Invoked");
        return result;
    }
}
