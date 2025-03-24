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
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;


public class NewUser {
   public static boolean registerUser(String Name, String Email, String Password) {
    // Vérifier si l'email existe déjà
    if (emailExists(Email)) {
        System.out.println("This email is already used.");
        return false;
    }

    String sql = "INSERT INTO Users(Name, Email, Password) VALUES(?, ?, ?)";

    try (Connection conn = DatabaseConnection.connect();
         PreparedStatement pstmt = conn.prepareStatement(sql)) {

        pstmt.setString(1, Name);
        pstmt.setString(2, Email);
        pstmt.setString(3, Password);

        pstmt.executeUpdate();
        System.out.println("Successfully added !");
        return true;
    } catch (Exception e) {
        System.out.println("Error : " + e.getMessage());
        return false;
    }
}

private static boolean emailExists(String Email) {
    String sql = "SELECT 1 FROM Users WHERE Email = ?";
    try (Connection conn = DatabaseConnection.connect();
         PreparedStatement pstmt = conn.prepareStatement(sql)) {

        pstmt.setString(1, Email);
        ResultSet rs = pstmt.executeQuery();
        return rs.next(); // Si l'email existe, retourne true
    } catch (Exception e) {
        System.out.println("Error during verification : " + e.getMessage());
        return false;
    }
}


 
}
