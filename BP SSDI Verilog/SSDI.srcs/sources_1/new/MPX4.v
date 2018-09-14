`timescale 1ns / 1ps
//////////////////////////////////////////////////////////////////////////////////
// Company: 
// Engineer: 
// 
// Create Date: 30.04.2018 00:22:11
// Design Name: 
// Module Name: MPX4
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


module MPX4 #(parameter SIZE = 32)
(
input clk,
input pop,
input [3:0] sel,
output reg MPX4_ack,
output reg dout
    );
    
reg [SIZE-1:0] i1_v = 32'b00100000000000000000000000000000; //1
reg [SIZE-1:0] DV1 = 32'b00000000010100011110110000000000; //0.01
reg [SIZE-1:0] DV2 = 32'b00000000000000000110100000000000; //0,00005
reg [SIZE-1:0] DV3 = 0;
reg [SIZE-1:0] DU1 = 32'b00000000010100011110110000000000; //0.01
reg [SIZE-1:0] DU2 = 32'b00000000000000000000000000000000; //0
reg [SIZE-1:0] DU3 = 0;
reg [SIZE-1:0] U = 32'b00000000000000000000000000000000; //0
reg [SIZE-1:0] i2 = 32'b01000000000000000000000000000000; //2
reg [SIZE-1:0] i1_2 = 32'b0001000000000000000000000000000;//0.5
reg [SIZE-1:0] i1_3 = 32'b0000101010101010101010101010101;//0.33333
reg [SIZE-1:0] dout_reg;
reg [4:0] count;
integer i;

initial begin
    i = 0;
    count = 0;
    dout = 0;
end

always @(sel) begin
    case (sel)
        4'b0000: begin dout_reg = i1_v; end
        4'b0001: dout_reg = DV1;
        4'b0010: dout_reg = DV2;
        4'b0011: dout_reg = DV3;
        4'b0100: dout_reg = DU1;
        4'b0101: dout_reg = DU2;
        4'b0110: dout_reg = DU3;
        4'b0111: dout_reg = U;
        4'b1000: dout_reg = i2;
        4'b1001: dout_reg = i1_2;
        4'b1010: dout_reg = i1_3;
        default: dout_reg = 0;
    endcase
end

// output
always @(posedge clk) begin
    if(pop) begin
        if(count == SIZE-1) begin
            count = 0;
            MPX4_ack = 1;
        end
        else begin
            dout = dout_reg[count];
            MPX4_ack = 0;
            count = count + 1;
        end    
    end
    else begin
        dout = dout_reg[0];
    end
end
endmodule
