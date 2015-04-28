package com.prgguru.android;

import android.app.Activity;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.TextView;

public class CheckLoginActivity extends Activity {
	//Set Error Status
	static boolean errored = false;
	Button b;
	TextView statusTV;
	EditText userNameET , passWordET;	
	ProgressBar webservicePG;
	String editTextUsername;
	boolean loginStatus;
	String editTextPassword;
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.main);
        //Name Text control
        userNameET = (EditText) findViewById(R.id.editText1);
        passWordET = (EditText) findViewById(R.id.editText2);
        //Display Text control
        statusTV = (TextView) findViewById(R.id.tv_result);
        //Button to trigger web service invocation
        b = (Button) findViewById(R.id.button1);
        //Display progress bar until web service invocation completes
        webservicePG = (ProgressBar) findViewById(R.id.progressBar1);
        //Button Click Listener
        b.setOnClickListener(new OnClickListener() {
            public void onClick(View v) {
                //Check if text controls are not empty
                if (userNameET.getText().length() != 0 && userNameET.getText().toString() != "") {
                	if(passWordET.getText().length() != 0 && passWordET.getText().toString() != ""){
                		editTextUsername = userNameET.getText().toString();
                		editTextPassword = passWordET.getText().toString();
                		statusTV.setText("");
                		//Create instance for AsyncCallWS
                		AsyncCallWS task = new AsyncCallWS();
                		//Call execute 
                		task.execute();
                	}
                	//If Password text control is empty
                	else{
                		statusTV.setText("Please enter Password");
                	}
                //If Username text control is empty
                } else {
                    statusTV.setText("Please enter Username");
                }
            }
        });
    }
    
    private class AsyncCallWS extends AsyncTask<String, Void, Void> {
		@Override
		protected Void doInBackground(String... params) {
			//Call Web Method
			loginStatus = WebService.invokeLoginWS(editTextUsername,editTextPassword,"authenticateUser");
			return null;
		}

		@Override
		//Once WebService returns response
		protected void onPostExecute(Void result) {
			//Make Progress Bar invisible
			webservicePG.setVisibility(View.INVISIBLE);
			Intent intObj = new Intent(CheckLoginActivity.this,HomeActivity.class);
			//Error status is false
			if(!errored){
				//Based on Boolean value returned from WebService
				if(loginStatus){
					//Navigate to Home Screen
					startActivity(intObj);
				}else{
					//Set Error message
					statusTV.setText("Login Failed, try again");
				}
			//Error status is true	
			}else{
				statusTV.setText("Error occured in invoking webservice");
			}
			//Re-initialize Error Status to False
			errored = false;
		}

		@Override
		//Make Progress Bar visible
		protected void onPreExecute() {
			webservicePG.setVisibility(View.VISIBLE);
		}

		@Override
		protected void onProgressUpdate(Void... values) {
		}
	}	
}
