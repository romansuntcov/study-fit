`timescale 1ns / 1ps
//////////////////////////////////////////////////////////////////////////////////
// Company: 
// Engineer: 
// 
// Create Date: 30.04.2018 19:27:27
// Design Name: 
// Module Name: ACC
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


module ACC #(parameter SIZE = 32)
(
input clk,
input push,
input pop,
input din,
output [SIZE-1:0] REG_out,
output reg ACC_ack,
output reg dout
    );
    
reg [SIZE-1:0] REG;
reg [4:0] count_pop;
reg [4:0] count_push;

assign REG_out = REG[SIZE-1:0];

initial begin
    REG = 0;
    count_pop = 0;
    count_push = 0;
    ACC_ack = 0;
    dout = 0;
end


always @(posedge clk) begin
    if(push) begin
            REG = REG >> 1;
            REG = {din, REG[SIZE-2:0]};
    end
    if(pop) begin
        if(count_pop == SIZE-1) begin
            count_pop = 0;
            ACC_ack = 1;
        end
        else begin
            dout = REG[count_pop];
            count_pop = count_pop + 1;
            ACC_ack = 0;
        end
    end
    else begin
        ACC_ack = 0;
        dout = REG[0];
    end
end
endmodule


