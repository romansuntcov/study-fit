`timescale 1ns / 1ps
//////////////////////////////////////////////////////////////////////////////////
// Company: 
// Engineer: 
// 
// Create Date: 01.05.2018 18:04:47
// Design Name: 
// Module Name: MPX2B
// Project Name: 
// Target Devices: 
// Tool Versions: 
// Description: 
// 
// Dependencies: 
// 
// Revision:
// Revision 0.01 - File Created
// Additional Comments:
// 
//////////////////////////////////////////////////////////////////////////////////


module MPX2B #(parameter SIZE = 32)(
input clk,
input RV,   //0
input XOR,  //1
input [1:0] sel,
output reg dout
    );
    
initial begin
    dout = 0;
end    
    
always @(*) begin
    if(sel == 0) dout = RV;
    else if(sel == 1'b1) dout = XOR;
    else dout = 0;
end    
endmodule
