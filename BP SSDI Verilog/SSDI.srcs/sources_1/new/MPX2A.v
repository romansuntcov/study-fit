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


module MPX2A #(parameter SIZE = 32)
(
input clk,
input pop,
input push,
input iDY1,
input iDY2,
input iDY3,
input iACC,
input [2:0] sel,
output [SIZE-1:0] MPX2A_ACC_out,
output reg MPX2A_ack,
output reg dout
    );
    
reg [SIZE-1:0] DY1_reg;
reg [SIZE-1:0] DY2_reg;
reg [SIZE-1:0] DY3_reg;
reg [SIZE-1:0] h = 32'b00000000010100011110110000000000;
reg [SIZE-1:0] ACC_reg;
reg [SIZE-1:0] dout_reg;
reg [SIZE-1:0] tmp_reg;         // для случайной параши
reg [4:0] count;
reg [4:0] count_push;
integer i;

initial begin
    i = 0;
    count = 0;
    count_push = 0;
    ACC_reg = 0;
    DY1_reg = 0;
    DY2_reg = 0;
    DY3_reg = 0;
    dout = 0;
end

always @(posedge clk) begin
    case (sel)
        3'b000: dout_reg = DY1_reg;
        3'b001: dout_reg = DY2_reg;
        3'b010: dout_reg = DY3_reg;
        3'b011: dout_reg = h;
        3'b100: dout_reg = ACC_reg;
        default: dout_reg = 0;
    endcase
end

assign MPX2A_ACC_out = dout_reg;//DY1_reg;//ACC_reg;

// input - push, на каждом такте записываем очередной бит, начиная с младших
always @(posedge clk) begin
    if(push) begin
                case (sel)
                    3'b000: begin DY1_reg = DY1_reg >> 1; DY1_reg = {iDY1, DY1_reg[SIZE-2:0]};  end
                    3'b001: begin DY2_reg = DY2_reg >> 1; DY2_reg = {iDY2, DY2_reg[SIZE-2:0]};  end
                    3'b010: begin DY3_reg = DY3_reg >> 1; DY3_reg = {iDY3, DY3_reg[SIZE-2:0]};  end
                    //3'b011: dout_reg = h;   --- read-only
                    3'b100: begin ACC_reg = ACC_reg >> 1; ACC_reg = {iACC, ACC_reg[SIZE-2:0]};  end
                    default: tmp_reg = 0;
                endcase  
    end
end

// output - pop
always @(posedge clk) begin
    if(pop) begin
        if(count == SIZE-1) begin
            count = 0;
            MPX2A_ack = 1;
        end
        else begin
            dout = dout_reg[count];
            MPX2A_ack = 0;
            count = count + 1;
        end
    end
    else begin
            dout = dout_reg[0];
        end
end

endmodule
