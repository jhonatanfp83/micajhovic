package com.example.mcjvapp;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class MainActivity extends AppCompatActivity {

    EditText edtCorreo, edtPassword;

    Button btnLogin, btnScanner;

    String URL =
            "https://oppressor-shadow-dealmaker.ngrok-free.dev/control_qr/login_app.php";

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);

        setContentView(R.layout.activity_main);

        edtCorreo = findViewById(R.id.edtCorreo);

        edtPassword = findViewById(R.id.edtPassword);

        btnLogin = findViewById(R.id.btnLogin);

        btnScanner = findViewById(R.id.btnScanner);

        /* LOGIN */

        btnLogin.setOnClickListener(v -> login());

        /* ABRIR SCANNER */

        btnScanner.setOnClickListener(v -> {

            Intent i =

                    new Intent(
                            MainActivity.this,

                            ScannerActivity.class);

            startActivity(i);

        });
    }

    private void login(){

        String correo =
                edtCorreo.getText().toString();

        String password =
                edtPassword.getText().toString();

        StringRequest request =
                new StringRequest(

                        Request.Method.POST,

                        URL,

                        response -> {

                            try{

                                JSONObject json =
                                        new JSONObject(response);

                                if(json.getString("status")
                                        .equals("success")){

                                    Intent i =
                                            new Intent(
                                                    MainActivity.this,
                                                    QRActivity.class);

                                    i.putExtra(
                                            "nombre",
                                            json.getString("nombre"));

                                    i.putExtra(
                                            "token",
                                            json.getString("token"));

                                    startActivity(i);

                                }else{

                                    Toast.makeText(
                                            this,
                                            "Datos incorrectos",
                                            Toast.LENGTH_SHORT).show();
                                }

                            }catch (Exception e){

                                Toast.makeText(
                                        this,
                                        e.toString(),
                                        Toast.LENGTH_LONG).show();
                            }

                        },

                        error -> Toast.makeText(
                                this,
                                error.toString(),
                                Toast.LENGTH_LONG).show()

                ){

                    @Override
                    protected Map<String, String> getParams(){

                        Map<String,String> params =
                                new HashMap<>();

                        params.put("correo",correo);

                        params.put("password",password);

                        return params;
                    }
                };

        RequestQueue queue =
                Volley.newRequestQueue(this);

        queue.add(request);
    }
}