`timescale 1ns / 1ps
//////////////////////////////////////////////////////////////////////////////////
// Company: 
// Engineer: 
// 
// Create Date: 30.04.2018 01:53:20
// Design Name: 
// Module Name: MUL
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


module MUL #(parameter SIZE = 32) 
(
input clk,
input A,
input B,
input push,
input pop,
input ena,
output reg MUL_ack,
output reg MUL_pop_ack,
output [SIZE-1:0] result_out,
output [SIZE-1:0] A_out,
output [SIZE-1:0] B_out,
output reg dout
);
    
reg [SIZE-1:0] A_reg;
reg [SIZE-1:0] B_reg;
reg [2*SIZE-1:0] result_reg;
reg [4:0] count_push;
reg [4:0] count_pop;

assign result_out = result_reg[2*SIZE-4:SIZE-3];
assign A_out = A_reg[SIZE-1:0];
assign B_out = B_reg[SIZE-1:0];

initial begin
    count_push = 0;
    count_pop = 0;
    A_reg = 0;
    B_reg = 0;
    result_reg = 0;
    dout = 0;
end

always @(posedge clk) begin
    if(ena) begin
        if(A_reg[SIZE-1] == 1) begin
            A_reg = ~A_reg + 1;
        end    
        else begin
            A_reg = A_reg;
        end
        
        if(B_reg[SIZE-1] == 1) begin
                    B_reg = ~B_reg + 1;
                end    
                else begin
                    B_reg = B_reg;
                end
                
        result_reg = A_reg*B_reg;
        
        if((A_reg[SIZE-1]^B_reg[SIZE-1]) == 1) begin
            result_reg = (~result_reg)+1;
        end  
        else begin
            result_reg = result_reg;
        end
        MUL_ack = 1;
    end
    else begin
        MUL_ack = 0;
    end
    // прием А и В
    if(push) begin
            A_reg = A_reg >> 1;
            A_reg = {A, A_reg[SIZE-2:0]};
            B_reg = B_reg >> 1;
            B_reg = {B, B_reg[SIZE-2:0]};
    end
    // отдача результата (отдаем обычную разрядность)
    if(pop) begin
        if(count_pop == SIZE-1) begin
            count_pop = 0;
            MUL_pop_ack = 1;
        end
        else begin
            dout = result_reg[count_pop+SIZE-3];
            count_pop = count_pop + 1;
            MUL_pop_ack = 0;
        end
    end
    else begin
        
        MUL_pop_ack = 0;
        dout = result_reg[0];
    end
end   

endmodule
