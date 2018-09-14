`timescale 1ns / 1ps
//////////////////////////////////////////////////////////////////////////////////
// Company: 
// Engineer: 
// 
// Create Date: 01.05.2018 17:48:18
// Design Name: 
// Module Name: XOR
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


module XOR #(parameter SIZE = 32)(
input clk,
input din,
input push,
input pop,
output [SIZE-1:0] REG_dop_out,
output reg XOR_ack,
output reg dout
    );

reg [SIZE-1:0] REG;
reg [SIZE-1:0] REG_dop;
reg [4:0] count_pop;
reg [4:0] count_push;

assign REG_dop_out = REG_dop[SIZE-1:0];

initial begin
    REG = 0;
    count_pop = 0;
    count_push = 0;
    XOR_ack = 0;
    dout = 0;
end

always @(posedge clk) begin
    REG_dop = ~REG + 1'b1;
end

always @(posedge clk) begin
    if(push) begin
            REG = REG >> 1;
            REG = {din, REG[SIZE-2:0]};
    end
    if(pop) begin
        if(count_pop == SIZE-1) begin
            count_pop = 0;
            XOR_ack = 1;
        end
        else begin
            dout = REG_dop[count_pop];
            count_pop = count_pop + 1;
            XOR_ack = 0;
        end
    end
    else begin
        XOR_ack = 0;
        dout = REG_dop[0];
    end
end
endmodule  

