package br.com.gpaengenharia.classes;

import android.app.AlarmManager;
import android.app.PendingIntent;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.util.Log;

import java.util.Calendar;

/**
 * Executa no boot do Android e seta o sincronismo
 * das tarefas via webservice de 10 em 10 minutos
 */
public class AgendaServico extends BroadcastReceiver {
    private final static int intervaloSincronismo = 60000; //milisegundos

    @Override
    public void onReceive(Context context, Intent intent) {
        Log.i("agenda", "agendando serviço");
        //intent para receber o alarme
        Intent intentAlarme = new Intent("EXECUTA_ALARME"); //setado no AndroidManifest
        PendingIntent intentPendente = PendingIntent.getBroadcast(context, 0, intentAlarme, 0);
        //define o tempo do sincronismo
        Calendar calendario = Calendar.getInstance();
        calendario.setTimeInMillis(System.currentTimeMillis()); //pega o tempo atual
        calendario.add(Calendar.SECOND, 10); //1 minuto depois de agora
        //agenda o sincronismo
        AlarmManager alarme = (AlarmManager) context.getSystemService(Context.ALARM_SERVICE);
        long tempo = calendario.getTimeInMillis();
        alarme.setRepeating(AlarmManager.RTC_WAKEUP,
                            tempo, //executar alarme apos 1 minuto a partir de agora
                            intervaloSincronismo , //repetir de 10 em 10 segundos
                            intentPendente //classe que o alarme repetira
        );
    }

}