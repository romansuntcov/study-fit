`timescale 1 ps/ 1 ps
`define CLK_PERIOD 10


module controller_sim #(parameter SIZE = 32) ();

wire clk;
wire ena;
wire rst;
wire MPX4_ack;
wire MPX2A_ack;
wire MUL_ack;
wire MUL_pop_ack;   // new
//wire MPX2B_ack;
wire SUM_ack;
wire XOR_ack;
wire ACC_ack;
wire ACC_pop;       // new
wire ACC_push;       // new
wire RV_ack;
//wire MPX4_en;
wire [3:0] MPX4_sel;
wire MPX4_pop;      // new
//wire MPX2A_en;
wire [2:0] MPX2A_sel;
wire MPX2A_push;    // new
wire MPX2A_pop;     // new
wire MUL_en;
wire MUL_push;      // new
wire MUL_pop;       // new
wire MPX2B_en;
wire [1:0] MPX2B_sel;
//wire SUM_en;
wire SUM_push;      // new
wire SUM_pop;       // new
wire XOR_en;
//wire ACC_en;
wire RV_en;
wire [9:0] state_out;

//MPX4 ONLY
wire MPX4_dout;

//MPX2A ONLY
wire MPX2A_dout;
wire [SIZE-1:0] MPX2A_out;

//MPX2A
wire MPX2A_iDY1;
wire MPX2A_iDY2;
wire MPX2A_iDY3;
wire MPX2A_iACC;

//MUL
wire MUL_dout;
//MUL ONLY
wire [SIZE-1:0] MUL_result_out;
wire [SIZE-1:0] MUL_A_out;
wire [SIZE-1:0] MUL_B_out;

// SUM
wire SUM_B;             // вход от MPX2B
wire SUM_dout;
//SUM ONLY
wire [SIZE-1:0] SUM_result_out;
wire [SIZE-1:0] SUM_A_out;
wire [SIZE-1:0] SUM_B_out;

//ACC
wire ACC_dout;
//ACC ONLY
wire [SIZE-1:0] REG_out;

// XOR ONLY
wire XOR_push;
wire XOR_pop;
wire [SIZE-1:0] XOR_dop_out;

//XOR
wire XOR_dout;

// RV ONLY
wire RV_push;
wire RV_pop;
wire [SIZE-1:0] RV_REG_out;

//RV
wire RV_dout;

//MPX2B
wire MPX2B_dout;

wire qqq;

//regs
reg clk_reg;
reg ena_reg;
reg rst_reg;

//reg2wire
assign clk = clk_reg;
assign ena = ena_reg;
assign rst = rst_reg;
assign SUM_B = 0;

// assign statements (if any)  
MPX2B mpx2b(
        .clk(clk),
        .RV(RV_dout),
        .XOR(XOR_dout),
        .sel(MPX2B_sel),
        .dout(MPX2B_dout)
        );
RV rv(
        .clk(clk),
        .din(ACC_dout),
        .push(RV_push),
        .pop(MUL_pop),
        .RV_out(RV_REG_out),
        .RV_ack(RV_ack),
        .dout(RV_dout)
        );

XOR xor1(
        .clk(clk),
        .din(ACC_dout),
        .push(XOR_push),
        .pop(MUL_pop),
        .REG_dop_out(XOR_dop_out),
        .XOR_ack(XOR_ack),
        .dout(XOR_dout)
);    

ACC acc(
        .clk(clk),
        .pop(ACC_pop),
        .push(ACC_push),
        .din(SUM_dout),
        .REG_out(REG_out),
        .ACC_ack(ACC_ack),
        .dout(ACC_dout)
        );

SUM sum(
        .clk(clk),
        .pop(SUM_pop),
        .push(SUM_push),
        .A(MUL_dout),
        .B(MPX2B_dout),
        .result_out(SUM_result_out),
        .A_out(SUM_A_out),
        .B_out(SUM_B_out),
        .SUM_ack(SUM_ack),
        .dout(SUM_dout)
        );


MUL mul(
        .clk(clk),
        .pop(MUL_pop),
        .push(MUL_push),
        .A(MPX4_dout),
        .B(MPX2A_dout),
        .ena(MUL_en),
        .MUL_ack(MUL_ack),
        .MUL_pop_ack(MUL_pop_ack),
        .result_out(MUL_result_out),
        .A_out(MUL_A_out),
        .B_out(MUL_B_out),
        .dout(MUL_dout)
);        
    
MPX2A mpx2a(
        .clk(clk),
        .pop(MPX2A_pop),
        .push(MPX2A_push),
        .iDY1(MUL_dout),
        .iDY2(MUL_dout),
        .iDY3(MUL_dout),
        .iACC(ACC_dout),
        .sel(MPX2A_sel),
        .MPX2A_ACC_out(MPX2A_out),
        .MPX2A_ack(MPX2A_ack),
        .dout(MPX2A_dout)
);

MPX4 mpx4(
        .clk(clk),
        .pop(MPX4_pop),
        .sel(MPX4_sel),
        .MPX4_ack(MPX4_ack),
        .dout(MPX4_dout)
);

controller i1 (
// port map - connection between master ports and signals/registers   
        .clk(clk),
        .ena(ena),
        .rst(rst),
        .MPX4_pop(MPX4_pop),    // +
        .MPX4_ack(MPX4_ack),
        .MPX2A_push(MPX2A_push),    // +
        .MPX2A_pop(MPX2A_pop),      // +
        .MPX2A_ack(MPX2A_ack),
        .MUL_push(MUL_push),        // +
        .MUL_pop(MUL_pop),          // +
        .MUL_ack(MUL_ack),
        .MUL_pop_ack(MUL_pop_ack),  // +
        //.MPX2B_ack(MPX2B_ack),
        .SUM_ack(SUM_ack),
        .XOR_push(XOR_push),
        .XOR_pop(XOR_pop),
        .XOR_ack(XOR_ack),
        .ACC_ack(ACC_ack),
        .RV_push(RV_push),
        .RV_pop(RV_pop),
        .RV_ack(RV_ack),
        //.MPX4_en(MPX4_en),
        .MPX4_sel(MPX4_sel),
        //.MPX2A_en(MPX2A_en),
        .MPX2A_sel(MPX2A_sel),
        .MUL_en(MUL_en),
        //.MPX2B_en(MPX2B_en),
        .MPX2B_sel(MPX2B_sel),
        .SUM_push(SUM_push),
        .SUM_pop(SUM_pop),
        .XOR_en(XOR_en),
        //.ACC_en(ACC_en),
        .ACC_push(ACC_push),
        .ACC_pop(ACC_pop),
        .state_out(state_out)
);

initial                                                
begin                                                  
  clk_reg = 0;
  ena_reg = 0;    
  rst_reg = 0;                                 
  $display("Running testbench");                       
end                                                    

always #(`CLK_PERIOD/2) clk_reg = (clk_reg !== 1) ? 1:0;                                      

//start test
initial begin
    #0 ena_reg = 1; rst_reg = 1;
    #10 rst_reg = 0;

//    #325 MPX4_ack_reg = 1; MPX2A_ack_reg = 1;
//    #10 MPX4_ack_reg = 0; MPX2A_ack_reg = 0;
//    #335 MUL_ack_reg = 1;
//    #10 MUL_ack_reg = 0;
end

endmodule