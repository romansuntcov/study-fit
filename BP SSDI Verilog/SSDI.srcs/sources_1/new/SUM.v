`timescale 1ns / 1ps
//////////////////////////////////////////////////////////////////////////////////
// Company: 
// Engineer: 
// 
// Create Date: 30.04.2018 19:05:52
// Design Name: 
// Module Name: SUM
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


module SUM #(parameter SIZE = 32)
(
input clk,
input A,
input B,
//input en,
input push,
input pop,
output [SIZE-1:0] result_out,
output [SIZE-1:0] A_out,
output [SIZE-1:0] B_out,
output reg SUM_ack,
output reg dout
);

reg [SIZE-1:0] A_reg;
reg [SIZE-1:0] B_reg;
reg [SIZE:0] result_reg;
reg [4:0] count_push;
reg [4:0] count_pop;

assign result_out = result_reg[SIZE-1:0];
assign A_out = A_reg[SIZE-1:0];
assign B_out = B_reg[SIZE-1:0];

initial begin
    A_reg = 32'b0;
    B_reg = 32'b0;
    result_reg = 32'b0;
    count_push = 5'b0;
    count_pop = 5'b0; 
    dout = 0;
    SUM_ack = 0;   
end

// прием входных значений 
always @(posedge clk) begin
    if(push) begin
            A_reg = A_reg >> 1;
            A_reg = {A, A_reg[SIZE-2:0]};
            B_reg = B_reg >> 1;
            B_reg = {B, B_reg[SIZE-2:0]};
    end
end

// сложение
always @(posedge clk) begin
    result_reg = A_reg + B_reg;
end    

// передача 
always @(posedge clk) begin
    if(pop) begin
        if(count_pop == SIZE-1) begin
            count_pop = 0;
            SUM_ack = 1;
        end
        else begin
            dout = result_reg[count_pop];
            count_pop = count_pop + 1;
            SUM_ack = 0;
        end
    end
    else begin
        dout = result_reg[0];
        SUM_ack = 0;
    end
end
        
endmodule
