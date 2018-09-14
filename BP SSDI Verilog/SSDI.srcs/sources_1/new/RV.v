`timescale 1ns / 1ps
/*module RV #(parameter SIZE = 32)
(
input clk,
input push,
input pop,
input din,
output [SIZE-1:0] RV_out,
output reg RV_ack,
output reg dout
    );
    
reg [SIZE-1:0] REG;
reg [4:0] count_pop;
reg [4:0] count_push;

assign RV_out = REG[SIZE-1:0];

initial begin
    REG = 48;           // Y0
    count_pop = 0;
    count_push = 0;
    RV_ack = 0;
    dout = 0;
end

// прием
always @(posedge clk) begin
    if(push) begin
        if(count_push == SIZE-1) begin
            count_push = 0;
        end
        else begin
            REG = {din, REG[SIZE-2:0]};
            REG = REG >> 1;
            count_push = count_push + 1;
        end
    end
//    if(pop) begin
//        if(count_pop == SIZE-1) begin
//            count_pop = 0;
//            RV_ack = 1;
//        end
//        else begin
//            dout = REG[count_pop];
//            count_pop = count_pop + 1;
//            RV_ack = 0;
//        end
//    end
    if(pop) begin
        if(count_pop == SIZE-1) begin
            count_pop = 0;
            RV_ack = 1;
        end
        else begin
            dout = REG[count_pop];
            count_pop = count_pop + 1;
            RV_ack = 0;
        end
    end
    else begin
        dout = 0;
    end

end
endmodule */

module RV #(parameter SIZE = 32)
(
input clk,
input push,
input pop,
input din,
output [SIZE-1:0] RV_out,
output reg RV_ack,
output reg dout
    );
    
reg [SIZE-1:0] REG;
reg [4:0] count_pop;
reg [4:0] count_push;

assign RV_out = REG[SIZE-1:0];

initial begin
    REG = 32'b11110000000000000000000000000000;
    count_pop = 0;
    count_push = 0;
    RV_ack = 0;
    dout = 0;
end

// прием
always @(posedge clk) begin
    if(push) begin
            REG = REG >> 1;
            REG = {din, REG[SIZE-2:0]};
    end
    if(pop) begin
        if(count_pop == SIZE-1) begin
            count_pop = 0;
            RV_ack = 1;
        end
        else begin
            dout = REG[count_pop];
            count_pop = count_pop + 1;
            RV_ack = 0;
        end
    end
    else begin
            RV_ack = 0;
            dout = REG[0];
        end
end
endmodule
