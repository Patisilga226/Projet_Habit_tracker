/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package habittrackerapp;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import static java.util.Collections.frequency;
import javax.swing.JComboBox;
import javax.swing.JOptionPane;
import javax.swing.JTextField;
import javax.swing.JToggleButton;

/** 
 *
 * @author user
 */
public class NewHab {
    private JTextField jTextField1, jTextField2, jTextField3, jTextField4, jTextField5,jTextField6;
    private JComboBox<String> jComboBox1, jComboBox2, jComboBox3;
    private Dashboard dashboard;
    private JToggleButton jToggleButton1,jToggleButton2,jToggleButton3,jToggleButton4,jToggleButton5,jToggleButton6,jToggleButton7;
    
   




    public NewHab(JTextField field1, JTextField field2, JTextField field3, JTextField field4, JTextField field5,JTextField field6, 
                  JComboBox<String> box1, JComboBox<String> box2, JComboBox<String> box3,JToggleButton jToggleButton1,JToggleButton jToggleButton2,JToggleButton jToggleButton3,JToggleButton jToggleButton4,JToggleButton jToggleButton5,JToggleButton jToggleButton6,JToggleButton jToggleButton7,Dashboard dashboard) {
        this.jTextField1 = field1;
        this.jTextField2 = field2;
        this.jTextField3 = field3;
        this.jTextField4 = field4;
        this.jTextField5 = field5;
        this.jTextField6 = field6;
        this.jComboBox1 = box1;
        this.jComboBox2 = box2;
        this.jComboBox3 = box3;
        this.dashboard = dashboard;
      
 // Utilisation des vrais JToggleButtons
    this.jToggleButton1 = jToggleButton1;
    this.jToggleButton2 = jToggleButton2;
    this.jToggleButton3 = jToggleButton3;
    this.jToggleButton4 = jToggleButton4;
    this.jToggleButton5 = jToggleButton5;
    this.jToggleButton6 = jToggleButton6;
    this.jToggleButton7 =jToggleButton7;

    }

    public void insertHabit() {
        Connection conn = DatabaseConnection.connect();
        if (conn == null) {
            JOptionPane.showMessageDialog(null, "Database connection failed!", "Error", JOptionPane.ERROR_MESSAGE);
            return;
          
           
        }

        String name = jTextField1.getText().trim();
        String description = jTextField2.getText().trim();
        String category = (String) jComboBox1.getSelectedItem();
        String reminderFrequency = (String) jComboBox2.getSelectedItem();
        String dailyFrequency = (String) jComboBox3.getSelectedItem();
         String begin_date = jTextField6.getText().trim();
         
         

       if (name.isEmpty() || description.isEmpty() || begin_date.isEmpty() || category.equals("Select category") || reminderFrequency.equals("Select")) {
        JOptionPane.showMessageDialog(null, "Please fill all fields!", "Error", JOptionPane.ERROR_MESSAGE);
        return; // Ne fermer pas la fenêtre, l'utilisateur peut recommencer
    }


        if (jTextField3.getText().trim().isEmpty() || jTextField4.getText().trim().isEmpty() || jTextField5.getText().trim().isEmpty()|| jTextField6.getText().trim().isEmpty()) {
            JOptionPane.showMessageDialog(null, "Time fields cannot be empty!", "Error", JOptionPane.ERROR_MESSAGE);
            return;
            
        }

        int reminderTime = 0, dailyTime = 0;
    try {
        int hour = Integer.parseInt(jTextField3.getText()); // Nombre d'heures (dailyTime)
        int minute = Integer.parseInt(jTextField4.getText()); // Nombre de minutes
        dailyTime = hour * 60; // Convertir les heures en minutes
        reminderTime = dailyTime + minute; // Additionner les minutes entrées

    } catch (NumberFormatException e) {
        JOptionPane.showMessageDialog(null, "Please enter valid numbers for time fields!", "Error", JOptionPane.ERROR_MESSAGE);
        return;
       
    }

           // Convertir reminderTime en String
    String reminderTimeString = String.valueOf(reminderTime); 
    
// Initialisation
StringBuilder frequency = new StringBuilder();
int TotalDays = 0;

// Vérifier et ajouter les jours sélectionnés
if (jToggleButton1.isSelected()) {
    frequency.append("Mon ");
    TotalDays++;
}
if (jToggleButton2.isSelected()) {
    frequency.append("Tue ");
    TotalDays++;
}
if (jToggleButton3.isSelected()) {
    frequency.append("Wed ");
    TotalDays++;
}
if (jToggleButton4.isSelected()) {
    frequency.append("Thu ");
    TotalDays++;
}
if (jToggleButton5.isSelected()) {
    frequency.append("Fri ");
    TotalDays++;
}
if (jToggleButton6.isSelected()) {
    frequency.append("Sat ");
    TotalDays++;
}
if (jToggleButton7.isSelected()) {
    frequency.append("Sun ");
    TotalDays++;
}

// Vérifier si aucun jour n'a été sélectionné
if (frequency.length() == 0) {
    frequency.append("No reminder"); // Mettre un vrai message neutre au lieu de "None"
} else {
    // Supprimer l'espace finale
    frequency.setLength(frequency.length() - 1);
}

       

        
        String sql = "INSERT INTO habits (Name, Description, Category, Frequency, Reminder_time, Reminder_frequency, Daily_Time, Daily_Frequency, begin_date, TotalDays) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

        try (PreparedStatement pstmt = conn.prepareStatement(sql)) {
            pstmt.setString(1, name);
            pstmt.setString(2, description);
            pstmt.setString(3, category);
            pstmt.setString(4, frequency.toString());
            pstmt.setString(5, String.valueOf(reminderTime)); // Conversion de reminderTime en String

            pstmt.setString(6, reminderFrequency);
            pstmt.setInt(7, dailyTime);
            pstmt.setString(8, dailyFrequency);
            

            pstmt.setString(9, begin_date); // Date entrée par l'utilisateur
             pstmt.setInt(10, TotalDays);

            pstmt.executeUpdate();
            JOptionPane.showMessageDialog(null, "Habit saved successfully.");
          dashboard.refreshDashboard();
// Appel de la méthode statique

        } catch (SQLException ex) {
            JOptionPane.showMessageDialog(null, "Error saving habit: " + ex.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);
            return;
        }
        
        

    }

  


}
    

