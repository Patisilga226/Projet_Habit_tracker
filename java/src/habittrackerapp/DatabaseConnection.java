/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package habittrackerapp;

/**
 *
 * @author user
 */
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;


public class DatabaseConnection {
    
      public static Connection connect() {
        Connection conn = null;
        try {
            // Connexion à la base de données SQLite
            conn = DriverManager.getConnection("jdbc:sqlite:Signup.db");
            System.out.println("Connexion réussie à SQLite !");
        } catch (SQLException e) {
            System.out.println("Erreur de connexion : " + e.getMessage());
        }
        return conn;
    }
    
}

