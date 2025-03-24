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
import javax.swing.*;
import java.awt.Color;

public class PlaceholderVerifier extends InputVerifier {
    private final String placeholder;

    public PlaceholderVerifier(String placeholder) {
        this.placeholder = placeholder;
    }

    @Override
    public boolean verify(JComponent input) {
        JTextField field = (JTextField) input;
        if (field.getText().equals(placeholder)) {
            field.setForeground(Color.GRAY);
            field.setText(placeholder);
            return false;
        }
        return true;
    }
}

