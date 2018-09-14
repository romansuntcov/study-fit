`timescale 1ns / 1ps

module controller(
    input clk,
    input ena,
    input rst,
    
    input MPX4_ack,
    input MPX2A_ack,
    input MUL_ack,
    input MUL_pop_ack,
    //input MPX2B_ack,
    input SUM_ack,
    input XOR_ack,
    input ACC_ack,
    input RV_ack,
    
    //output reg MPX4_en,         
    output reg [3:0] MPX4_sel,
    output reg MPX4_pop, 
    
    //output reg MPX2A_en,
    output reg [2:0] MPX2A_sel,
    output reg MPX2A_pop,           
    output reg MPX2A_push,          //?????? ????? (dy1..3, acc)
    
    output reg MUL_en,      // ????? ?????????
    output reg MUL_push,
    output reg MUL_pop,
    
    output reg RV_push,
    output reg RV_pop,
    
      
    output reg [1:0] MPX2B_sel,
    
    output reg SUM_en,
    output reg SUM_push,
    output reg SUM_pop,
    
    output reg XOR_en,
    output reg XOR_push,
    output reg XOR_pop,
    
    //output reg ACC_en,
    output reg ACC_push,
    output reg ACC_pop,
    
    output reg [9:0] state_out
    );

reg [9:0] state;
parameter S0 = 0, S1 = 1, S2 = 2, S3 = 3, S4 = 4, S5 = 5, S6 = 6, S7 = 7, S8 = 8, S9 = 9, S10 = 10, S11 = 11, S12 = 12, S13 = 13, S14 = 14, S15 = 15, S16 = 16, S17 = 17, S18 = 18, S19 = 19, S20 = 20, S21 = 21, S22 = 22, S23 = 23, S24 = 24, S25 = 25, S26 = 26, S27 = 27, S28 = 28, S29 = 29, S30 = 30, S31 = 31, S32 = 32, S33 = 33, S34 = 34, S35 = 35, S36 = 36, S37 = 37, S38 = 38, S39 = 39, S40 = 40, S41 = 41, S42 = 42, S43 = 43, S44 = 44, S45 = 45, S46 = 46, S47 = 47, S48 = 48, S49 = 49, S50 = 50, S51 = 51, S52 = 52, S53 = 53, S54 = 54, S55 = 55, S56 = 56, S57 = 57, S58= 58, S59=59, S60 = 60, S61=61, S62 = 62, S63 = 63, S64 = 64, S65 = 65, S66 = 66, S67=67, S68=68, S69=69, S70=70, S71=71, S72=72, S73=73, S74=74, S75=75, S76=76, S77=77, S78=78, S79=79, S80=80, S81=81, S82=82, S83=83, S84=84, S85=85, S86=86, S87=87, S88=88, S89=89, S90=90, S91=91, S92=92, S93=93, S94=94, S95=95, S96=96, S97=97, S98=98, S99=99, S100=100, S101=101, S102=102, S103=103, S104=104, S105=105, S106=106, S107=107, S108=108, S109=109, S110=110, S111=111, S112=112, S113=113, S114=114, S115=115, S116=116, S117=117, S118=118, S119=119, S120=120, S121=121, S122=122, S123=123, S124=124, S125=125, S126=126, S127=127, S128=128, S129=129, S130=130, S131=131, S132=132, S133=133, S134=134;

always @(posedge clk, posedge rst) begin
    if (rst) 
        state <= 0;
    else if (state == S0 )
        state <= S1;
    else if (state == S1 )
        state <= S2;
    else if (state == S2 && MPX4_ack && MPX2A_ack)
       state <= S3;
    else if (state == S3 )
        state <= S4;
    else if (state == S4 && MUL_ack)
        state <= S5;
    else if (state == S5 )
        state <= S6;
    else if (state == S6 && MUL_pop_ack)
       state <= S7;
    else if (state == S7 )
       state <= S8;
    else if (state == S8 && SUM_ack)
       state <= S9;
    else if (state == S9)
       state <= S10;
    else if (state == S10 && ACC_ack)
       state <= S11;
    else if (state == S11 )
       state <= S12;
    else if (state == S12)
        state <= S13;
    else if (state == S13 && MPX4_ack && MPX2A_ack)
        state <= S14;
    else if (state == S14 )
        state <= S15;
    else if (state == S15 && MUL_ack)
       state <= S16;
    else if (state == S16 )
       state <= S17;
    else if (state == S17)
       state <= S18;
    else if (state == S18 && MUL_pop_ack)
       state <= S19;
    else if (state == S19 )
        state <= S20;
    else if (state == S20 )
       state <= S21;
    else if (state == S21 && MUL_pop_ack)
       state <= S22;
    else if (state == S22)
       state <= S23;
    else if (state == S23 && SUM_ack)
       state <= S24;
    else if (state == S24 )
       state <= S25;
    else if (state == S25 && ACC_ack)
       state <= S26;
    else if (state == S26)
       state <= S27;
    else if (state == S27)
       state <= S28;
    else if (state == S28 && MPX4_ack && MPX2A_ack)
        state <= S29;
    else if (state == S29 )
       state <= S30;
    else if (state == S30 && MUL_ack)
       state <= S31;
    else if (state == S31)
       state <= S32;
    else if (state == S32)
       state <= S33;
    else if (state == S33 && MUL_pop_ack)
       state <= S34;
    else if (state == S34 )
       state <= S35;
    else if (state == S35 && SUM_ack)
       state <= S36;
    else if (state == S36 )
       state <= S37;
    else if (state == S37 && ACC_ack)
       state <= S38;
    else if (state == S38 )
       state <= S39;
    else if (state == S39 )
       state <= S40;
    else if (state == S40 && MPX4_ack && MPX2A_ack)
       state <= S41;
    else if (state == S41)
       state <= S42;
    else if (state == S42 && MUL_ack)
       state <= S43;
    else if (state == S43 )
       state <= S44;
    else if (state == S44 && MUL_pop_ack && XOR_ack)
      state <= S45;
    else if (state == S45 )
       state <= S46;
    else if (state == S46 && SUM_ack)
       state <= S47;
    else if (state == S47 )
       state <= S48;
    else if (state == S48 && ACC_ack)
       state <= S49;
    else if (state == S49)
       state <= S50;
    else if (state == S50 && MPX4_ack && MPX2A_ack)
       state <= S51;
    else if (state == S51)
       state <= S52;
    else if (state == S52 && MUL_ack)
       state <= S53;
    else if (state == S53)
       state <= S54;
    else if (state == S54 && MUL_pop_ack)
       state <= S55;
    else if (state == S55)
       state <= S56;
    else if (state == S56 && SUM_ack)
       state <= S57;
    else if (state == S57)
       state <= S58;
    else if (state == S58 && ACC_ack)
       state <= S59;
    else if (state == S59)
       state <= S60;
    else if (state == S60 && MPX4_ack && MPX2A_ack)
       state <= S61;
    else if (state == S61)
       state <= S62;
    else if (state == S61)
       state <= S62;
    else if (state == S62 && MUL_ack)
       state <= S63;
   else if (state == S63 )
        state <= S64;
    else if (state == S64  && MUL_pop_ack)
        state <= S65;
    else if (state == S65)
        state <= S66;
    else if (state == S66 && MUL_pop_ack)
        state <= S67;
    else if (state == S67)
        state <= S68;
    else if (state == S68 && SUM_ack)
         state <= S69;
    else if (state == S69)
          state <= S70;
    else if (state == S70 && ACC_ack)
          state <= S71;
    else if (state == S71)
          state <= S72;
    else if (state == S72)
          state <= S73;
    else if (state == S73 && MPX4_ack && MPX2A_ack)
          state <= S74;
    else if (state == S74  )
          state <= S75;
    else if (state == S75 && MUL_ack)
          state <= S76;
    else if (state == S76)
          state <= S77;
    else if (state == S77 && MUL_pop_ack)
           state <= S78;
     else if (state == S78)
           state <= S79;
     else if (state == S79 && SUM_ack)
          state <= S80;
     else if (state == S80)
          state <= S81;
     else if (state == S81 && ACC_ack)
          state <= S82;
     else if (state == S82)
          state <= S83;
     else if (state == S83 && MPX4_ack && MPX2A_ack)
          state <= S84;
     else if (state == S84)
          state <= S85;
     else if (state == S85 && MUL_ack)
          state <= S86;
     else if (state == S86)
          state <= S87;
     else if (state == S87 && MUL_pop_ack)
          state <= S88;
     else if (state == S88)
          state <= S89;
     else if (state == S89 && SUM_ack)
          state <= S90;
     else if (state == S90 )
          state <= S91;
     else if (state == S91 && ACC_ack)
          state <= S92;
     else if (state == S92 )
          state <= S93;
     else if (state == S93 && MPX4_ack && MPX2A_ack)
          state <= S94;
     else if (state == S94 )
          state <= S95;
     else if (state == S95 && MUL_ack)
          state <= S96;
     else if (state == S96 )
          state <= S97;
     else if (state == S97 && MUL_pop_ack)
          state <= S98;
     else if (state == S98 )
          state <= S99;
     else if (state == S99 && SUM_ack)
          state <= S100;
     else if (state == S100)
          state <= S101;                                                                                          
     else if (state == S101 && ACC_ack)
          state <= S102;
     else if (state == S102)
          state <= S103;
     else if (state == S103 && MPX4_ack && MPX2A_ack)
          state <= S104;
     else if (state == S104)
          state <= S105;
     else if (state == S105 && MUL_ack)
          state <= S106;
     else if (state == S106)
          state <= S107; 
     else if (state == S107 && MUL_pop_ack)
          state <= S108; 
     else if (state == S108)
          state <= S109;
     else if (state == S109 && SUM_ack)
          state <= S110;
     else if (state == S110)
          state <= S111;
     else if (state == S111 && ACC_ack)
          state <= S112;
     else if (state == S112)
          state <= S113;
     else if (state == S113 && MPX4_ack && MPX2A_ack)
          state <= S114;
     else if (state == S114)
          state <= S115;
     else if (state == S115 && MUL_ack)
          state <= S116;
     else if (state == S116)
          state <= S117;
     else if (state == S117 && MUL_pop_ack)
          state <= S118;
     else if (state == S118)
          state <= S119;
     else if (state == S119 && SUM_ack)
          state <= S120;
     else if (state == S120)
          state <= S121;                                                                                                                                                                                                                                                                                                                                                                                                  
     else if (state == S121 && ACC_ack)
          state <= S122;                                                                                                                                                                                                                                                                                                                                                                                                  
     else if (state == S122)
           state <= S123;                                                                                                                                                                                                                                                                                                                                                                                                  
     else if (state == S123 && MPX4_ack && MPX2A_ack)
           state <= S124;
     else if (state == S124 )
           state <= S125;      
     else if (state == S125 && MUL_ack)
           state <= S126;
     else if (state == S126 )
           state <= S127;
     else if (state == S127 && MUL_pop_ack)
           state <= S128;
     else if (state == S128 )
           state <= S129;
     else if (state == S129 && MUL_pop_ack)
           state <= S130;
     else if (state == S130 )
           state <= S131; 
     else if (state == S131 && SUM_ack)
           state <= S132;
     else if (state == S132 )
           state <= S133;
     else if (state == S133 && ACC_ack)
           state <= S134;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
    end                

always @ (state) begin
    case (state)
        S0: begin   state_out = 0; 
                    MPX4_sel = 4'b0111; // sel U
                    MPX2A_sel = 3'b011;    
            end         
        S1: begin   state_out = 1;
                    MPX4_pop = 1;
                    MPX2A_pop = 1; 
            end       
        S2: begin   state_out = 2;
                    MUL_push = 1;           
            end
        S3: begin   state_out = 3; 
                    MPX4_pop = 0;
                    MPX2A_pop = 0;
                    MUL_push = 0;
            end
        S4: begin   state_out = 4;
                    MUL_en = 1;
            end           
        S5: begin   state_out = 5; 
                    MUL_en = 0; 
            end
        S6: begin   state_out = 6; 
                    MUL_pop = 1;
                    SUM_push = 1;     
            end
        S7: begin   state_out = 7;
                     MUL_pop = 0;
                     SUM_push = 0;
            end
        S8: begin   state_out = 8;
                    SUM_pop = 1;
                    ACC_push = 1;
            end
        S9: begin   state_out = 9;
                    SUM_pop = 0;
                    ACC_push = 0;
            end                    
        S10: begin  state_out = 10;
                    ACC_pop = 1;
                    MPX2A_sel = 3'b100;
                    MPX2A_push = 1;
                    
            end                    
        S11: begin  state_out = 11;
                    ACC_pop = 0;
                    MPX2A_push = 0;
            end
        S12: begin state_out = 12;
                    MPX4_sel = 4'b0;
                    MPX2A_sel = 3'b100;
            end
        S13: begin state_out = 13;
                    MPX4_pop = 1;
                    MPX2A_pop = 1;
                    MUL_push = 1;    
             end                              
        S14: begin state_out = 14;
                    MPX4_pop = 0;
                    MPX2A_pop = 0;
                    MUL_push = 0;
            end
        S15: begin state_out = 15;
                   MUL_en = 1;
            end
        S16: begin  state_out = 16;
                    MUL_en = 0;
            end
        S17: begin  state_out = 17;
                    MPX2A_sel = 3'b0;
            end
        S18: begin state_out = 18;
                    MUL_pop = 1; 
                    MPX2A_push = 1;
            end
        S19: begin state_out = 19;
                    MUL_pop = 0; 
                    MPX2A_push = 0;  
            end
        S20: begin  state_out = 20;
                    MPX2B_sel = 0;
             end
        S21: begin state_out = 21;
                    RV_pop = 1;
                    MUL_pop = 1;
                    SUM_push = 1; 
             end
        S22: begin state_out = 22;   
                    RV_pop = 0;
                    MUL_pop = 0;
                    SUM_push = 0;
            end
        S23: begin state_out = 23; 
                    SUM_pop = 1;
                    ACC_push = 1;
             end
        S24: begin state_out = 24;
                    SUM_pop = 0;
                    ACC_push = 0; 
             end          
        S25: begin state_out = 25;
                    ACC_pop = 1;
                    RV_push = 1;
             end 
        S26: begin state_out = 26;
                    ACC_pop = 0;
                    RV_push = 0; 
             end
        S27: begin state_out = 27;
                    MPX4_sel = 4'b0001; // sel DV1
                    MPX2A_sel = 3'b000;  
             end
        S28: begin state_out = 28; 
                    MPX4_pop = 1;   
                    MPX2A_pop = 1;  
                    MUL_push = 1;        
             end       
        S29: begin state_out = 29;
                    MPX4_pop = 0;   
                    MPX2A_pop = 0;  
                    MUL_push = 0;  
             end
        S30: begin state_out = 30;
                    MUL_en = 1;
             end
        S31: begin state_out = 31;
                    MUL_en = 0;
             end
        S32: begin state_out = 32;
                    MPX2B_sel = 2'b11;
             end
        S33: begin  state_out = 33;
                     MUL_pop = 1;
                     SUM_push = 1;
             end
        S34: begin state_out = 34;
                    MUL_pop = 0;
                    SUM_push = 0;
             end
        S35: begin state_out = 35;
                    SUM_pop = 1;
                    ACC_push = 1;
             end
        S36: begin state_out = 36;
                    SUM_pop = 0;
                    ACC_push = 0;
             end
        S37: begin state_out = 37;
                    ACC_pop = 1;
                    XOR_push = 1;
             end
        S38: begin state_out = 38;
                    ACC_pop = 0;
                    XOR_push = 0;
             end
        S39: begin state_out = 39;
                    MPX4_sel = 4'b0100; // sel DU1
                    MPX2A_sel = 3'b011;
                    MPX2B_sel = 1'b1;
             end
        S40: begin state_out = 40;
                   MPX4_pop = 1;
                   MPX2A_pop = 1;
                   MUL_push = 1;
             end
        S41: begin state_out = 41;
                    MPX4_pop = 0;   
                    MPX2A_pop = 0;  
                    MUL_push = 0;   
             end    
        S42: begin state_out = 42;
                    MUL_en = 1;
             end
        S43: begin state_out = 43;
                    MUL_en = 0;
             end    
        S44: begin state_out = 44;
                    XOR_pop = 1;
                    MUL_pop = 1;
                    SUM_push = 1;    
             end
        S45: begin state_out = 45;
                    XOR_pop = 0;
                    MUL_pop = 0;
                    SUM_push = 0;        
             end
        S46: begin state_out = 46;
                    SUM_pop = 1;
                    ACC_push = 1;
             end
        S47: begin state_out = 47;
                    SUM_pop = 0;
                    ACC_push = 0;
             end
        S48: begin state_out = 48;
                    ACC_pop = 1;
                    MPX2A_sel = 3'b100;
                    MPX2A_push = 1;
             end
        S49: begin state_out = 49;
                    ACC_pop = 0;
                    MPX2A_push = 0;
                    MPX4_sel = 4'b1001;
             end
        S50: begin state_out = 50;
                    MPX2A_pop = 1;
                    MPX4_pop = 1;
                    MUL_push = 1;
             end
        S51: begin state_out = 51;
                    MPX2A_pop = 0;
                    MPX4_pop = 0;
                    MUL_push = 0;
             end
        S52: begin state_out = 52;
                    MUL_en = 1;
             end
        S53: begin state_out = 53;
                    MUL_en = 0;
                    MPX2B_sel = 3'b11;
             end
        S54: begin state_out = 54;
                    MUL_pop = 1;
                    SUM_push = 1;
             end
        S55: begin state_out = 55;
                    MUL_pop = 0;
                    SUM_push = 0;
             end
        S56: begin state_out = 56;
                    SUM_pop = 1;
                    ACC_push = 1;
              end  
        S57: begin state_out = 57;
                    SUM_pop = 0;
                    ACC_push = 0;
              end
        S58: begin state_out = 58;
                    MPX2A_sel = 3'b100;
                    ACC_pop = 1;
                    MPX2A_push = 1;
             end  
        S59: begin state_out = 59;
                    MPX2A_push = 0;
                    ACC_pop = 0;
                    MPX4_sel = 4'b0000;
             end 
        S60: begin state_out = 60;
                    MPX4_pop = 1;
                    MPX2A_pop = 1;
                    MUL_push = 1;
             end 
        S61: begin state_out = 61;
                    MPX4_pop = 0;
                    MPX2A_pop = 0;
                    MUL_push = 0;
         end
         S62: begin state_out = 62;
                    MUL_en = 1;
         end 
         S63: begin state_out = 63;
                    MUL_en = 0;
                    MPX2A_sel =  3'b001;
         end
         S64: begin state_out = 64;
                     MUL_pop = 1;
                     MPX2A_push = 1;      
         end
         S65: begin state_out = 65;
                    MUL_pop = 0;
                    MPX2A_push = 0; 
                    MPX2B_sel = 0;
         end 
         S66: begin state_out = 66;
                    RV_pop = 1;
                    MUL_pop = 1;
                    SUM_push = 1;      
         end 
         S67: begin state_out = 67;
                    RV_pop = 0;
                    MUL_pop = 0;
                    SUM_push = 0;
         end
         S68: begin state_out = 68;
                    SUM_pop = 1;
                    ACC_push = 1;
         end  
         S69: begin state_out = 69;
                    SUM_pop = 0;
                    ACC_push = 0;
         end
         S70: begin state_out = 70;
                    ACC_pop = 1;
                    RV_push = 1;
         end
         S71: begin state_out = 71;
                    ACC_pop = 0;
                    RV_push = 0;   
        end
        S72: begin state_out = 72;
                    MPX4_sel = 4'b0010;
                    MPX2A_sel = 3'b000;
        end 
        S73: begin state_out = 73;
                    MPX4_pop = 1;
                    MPX2A_pop = 1;
                    MUL_push = 1;
        end
        S74: begin state_out = 74;
                    MPX4_pop = 0;
                    MPX2A_pop = 0;
                    MUL_push = 0;
        end
        S75: begin state_out = 75;
                    MUL_en = 1;
        end
        S76: begin state_out = 76;
                    MUL_en = 0;
                    MPX2B_sel = 3'b11;
        end
        S77: begin state_out = 77;
                    MUL_pop = 1;       
                    SUM_push = 1;
        end
        S78: begin state_out = 78;
                    SUM_push = 0;
                    MUL_pop = 0;
        end
        S79: begin state_out = 79;
                    SUM_pop = 1;
                    ACC_push = 1;
         end
        S80: begin state_out = 80;
                    SUM_pop = 0;
                    ACC_push = 0;
                
        end
        S81: begin state_out = 81;
                    ACC_pop = 1;
                    XOR_push = 1;
        end
        S82: begin state_out = 82;  
                    ACC_pop = 0;
                    XOR_push = 0;
                    MPX2B_sel = 1'b1;
                    MPX2A_sel = 3'b001;
                    MPX4_sel = 4'b1000;
        end
        S83: begin state_out = 83; 
                    MPX2A_pop = 1;
                    MPX4_pop = 1;
                    MUL_push = 1;
         end
         S84: begin state_out = 84;
                    MPX2A_pop = 0;
                    MPX4_pop = 0;
                    MUL_push = 0;
        end
        S85: begin state_out = 85;
                    MUL_en = 1;
        end
        S86: begin state_out = 86;
                    MUL_en = 0;
                    MPX2B_sel = 3'b11;
        end
        S87: begin state_out = 87;
                    MUL_pop = 1;
                    SUM_push = 1;
        end 
        S88: begin state_out = 88;
                    MUL_pop = 0;
                    SUM_push = 0;
       end 
       S89: begin state_out = 89;
                    SUM_pop = 1;
                    ACC_push = 1;
            end
       S90: begin state_out = 90;
                    SUM_pop = 0;
                    ACC_push = 0;
            end
       S91: begin state_out = 91;
                    MPX2A_sel = 3'b100;
                    ACC_pop = 1;
                    MPX2A_push = 1;
            end
       S92: begin state_out = 92;
                    MPX2A_push = 0;
                    ACC_pop = 0;
                    MPX4_sel = 4'b0001;
            end
      S93: begin state_out = 93;
                    MPX2A_pop = 1;
                    MPX4_pop = 1;
                    MUL_push = 1;
            end
      S94: begin state_out = 94;
                    MPX2A_pop = 0;
                    MPX4_pop = 0;
                    MUL_push = 0;
            end
      S95: begin state_out = 95;
                    MUL_en = 1;
            end
      S96: begin state_out = 96;
                    MUL_en = 0;
                    MPX2B_sel = 1'b1;
            end
      S97: begin state_out = 97;
                    MUL_pop = 1;
                    XOR_pop = 1;
                    SUM_push = 1;          
            end
      S98: begin state_out = 98;
                    SUM_push = 0;
                    MUL_pop = 0;
                    XOR_pop = 0;
            end
      S99: begin state_out = 99;
                    SUM_pop = 1;
                    ACC_push = 1;      
           end
       S100: begin state_out = 100;
                    SUM_pop = 0;
                    ACC_push = 0;      
             end 
       S101: begin state_out = 101;
                    ACC_pop = 1;
                    XOR_push = 1;    
             end
       S102: begin state_out = 102;
                    ACC_pop = 0;
                    XOR_push = 0;
                    MPX4_sel = 4'b0101;
                    MPX2A_sel = 3'b011;
             end
       S103: begin state_out = 103;
                    MPX4_pop = 1;
                    MPX2A_pop = 1;
                    MUL_push = 1;
             end
       S104: begin state_out = 104;
                    MPX4_pop = 0;
                    MPX2A_pop = 0;
                    MUL_push = 0; 
             end
       S105: begin state_out = 105;
                    MUL_en = 1; 
             end
       S106: begin state_out = 106;
                    MUL_en = 0;
                    MPX2B_sel = 1'b1; 
             end
       S107: begin state_out = 107;
                    MUL_pop = 1;
                    XOR_pop = 1;
                    SUM_push = 1;  
             end
       S108: begin state_out = 108;
                    SUM_push = 0;
                    MUL_pop = 0;
                    XOR_pop = 0;  
             end
       S109: begin state_out = 109;
                    SUM_pop = 1;
                    ACC_push = 1;  
             end
       S110: begin state_out = 110;
                    SUM_pop = 0;
                    ACC_push = 0;
                    MPX2A_sel = 3'b100; 
             end
       S111: begin state_out = 111;
                     ACC_pop = 1;
                     MPX2A_push = 1; 
             end
       S112: begin state_out = 112;
                     MPX2A_push = 0;
                     ACC_pop = 0;
                     MPX4_sel = 4'b1010; 
             end
       S113: begin state_out = 113;
                     MPX2A_pop = 1;
                     MPX4_pop = 1;
                     MUL_push = 1;
             end
       S114: begin state_out = 114;
                     MPX2A_pop = 0;
                     MPX4_pop = 0;
                     MUL_push = 0;  
             end
       S115: begin state_out = 115;
                     MUL_en = 1; 
              end
       S116: begin state_out = 116;
                     MUL_en = 0; 
                     MPX2B_sel = 3'b11;      
             end
       S117: begin state_out = 117;
                     MUL_pop = 1;
                     SUM_push = 1;   
             end
       S118: begin state_out = 118;
                     SUM_push = 0;
                     MUL_pop = 0;      
       end
       S119: begin state_out = 119;
                     SUM_pop = 1;
                     ACC_push = 1;        
       end
       S120: begin state_out = 120;
                     SUM_pop = 0;
                     ACC_push = 0;
                     MPX2A_sel = 3'b100;       
              end
       S121: begin state_out = 121;
                     ACC_pop = 1;
                     MPX2A_push = 1;  
              end
       S122: begin state_out = 122;
                     MPX2A_push = 0;
                     ACC_pop = 0;
                     MPX4_sel = 4'b0000;
              end
       S123: begin state_out = 123;
                     MPX2A_pop = 1;
                     MPX4_pop = 1;
                     MUL_push = 1;          
              end
       S124: begin state_out = 124;
                     MPX2A_pop = 0;
                     MPX4_pop = 0;
                     MUL_push = 0;          
             end       
       S125: begin state_out = 125;
                     MUL_en = 1; 
             end
       S126: begin state_out = 126;
                     MUL_en = 0;       
                     MPX2A_sel =  3'b010;
             end              
       S127: begin state_out = 127;
                     MUL_pop = 1;
                     MPX2A_push = 1;
               end
       S128: begin state_out = 128;
                     MUL_pop = 0;
                     MPX2A_push = 0; 
                     MPX2B_sel = 0;
               end
       S129: begin state_out = 129;
                     RV_pop = 1;
                     MUL_pop = 1;
                     SUM_push = 1;      
                end
       S130: begin state_out = 130;
                     RV_pop = 0;
                     MUL_pop = 0;
                     SUM_push = 0;        
              end
        S131: begin state_out = 131;
                     SUM_pop = 1;
                     ACC_push = 1;        
              end
        S132: begin state_out = 132;
                     SUM_pop = 0;
                     ACC_push = 0;   
              end
        S133: begin state_out = 133;
                     ACC_pop = 1;
                     RV_push = 1;
              end
        S134: begin state_out = 134;
                     ACC_pop = 0;
                     RV_push = 0;
            end                                                                                                                                
        default:
            state_out = 134;
    endcase
end
   
endmodule
            
