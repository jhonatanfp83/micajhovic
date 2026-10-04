package com.example.mcjvapp;

import android.content.Intent;
import android.os.Bundle;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.Request;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import com.google.zxing.integration.android.IntentIntegrator;
import com.google.zxing.integration.android.IntentResult;

import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class ScannerActivity
        extends AppCompatActivity {

    /* La URL base sale de res/values/strings.xml (api_base_url) */
    private String urlApi(){
        return getString(R.string.api_base_url) + "validar_qr.php";
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);

        IntentIntegrator integrator =
                new IntentIntegrator(this);

        integrator.setPrompt(
                "Escanear QR");

        integrator.setOrientationLocked(false);

        integrator.initiateScan();
    }

    @Override
    protected void onActivityResult(

            int requestCode,

            int resultCode,

            Intent data) {

        IntentResult result =

                IntentIntegrator.parseActivityResult(

                        requestCode,
                        resultCode,
                        data);

        if(result != null){

            if(result.getContents()!=null){

                validarQR(
                        result.getContents());

            }else{

                finish();
            }

        }else{

            super.onActivityResult(
                    requestCode,
                    resultCode,
                    data);
        }
    }

    private void validarQR(String token){

        StringRequest request =

                new StringRequest(

                        Request.Method.POST,

                        urlApi(),

                        response -> {

                            try{

                                JSONObject json =
                                        new JSONObject(response);

                                if(json.getString("status")
                                        .equals("success")){

                                    Intent i = new Intent(

                                            ScannerActivity.this,

                                            ResultadoActivity.class);

                                    i.putExtra(
                                            "estado",
                                            "APROBADO");

                                    i.putExtra(
                                            "nombre",
                                            json.getString("nombre"));

                                    i.putExtra(
                                            "tipo",
                                            json.getString("tipo"));

                                    startActivity(i);

                                    finish();

                                }else{

                                    Intent i = new Intent(

                                            ScannerActivity.this,

                                            ResultadoActivity.class);

                                    i.putExtra(
                                            "estado",
                                            "DENEGADO");

                                    startActivity(i);

                                    finish();
                                }

                            }catch(Exception e){

                                Toast.makeText(

                                        this,

                                        e.toString(),

                                        Toast.LENGTH_LONG).show();
                            }

                        },

                        error -> Toast.makeText(

                                this,

                                "ERROR SERVIDOR",

                                Toast.LENGTH_LONG).show()

                ){

                    @Override
                    protected Map<String,String>
                    getParams(){

                        Map<String,String> params =
                                new HashMap<>();

                        params.put(
                                "token",
                                token);

                        return params;
                    }
                };

        Volley.newRequestQueue(this)
                .add(request);
    }
}